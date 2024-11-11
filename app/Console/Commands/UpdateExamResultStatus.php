<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExamResult;
use App\Models\Question;

class UpdateExamResultStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateExamResultStatus:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Retrieve all ongoing exams where the end time has passed
        $examResults = ExamResult::where('status', 'ongoing')
            ->where('end_time', '<', now())
            ->get();
    
        foreach ($examResults as $examResult) {
            // Retrieve stored user answers and user ID
            $user_answer = json_decode($examResult->answers, true) ?? [];
            $user = $examResult->user;
    
            // Initialize scoring and marking variables
            $score = 0;
            $correctAnswer = 0;
            $incorrect = 0;
            $totalMarks = 0;
            $incorrectMarks = 0;
            $wrongQuestionIds = [];
    
            // Determine total marks if in manual mode
            $totalMarks = $examResult->point_type == "manual" ? $examResult->point * count($user_answer) : 0; 
    
            foreach ($user_answer as $answer) {
                if (!isset($answer['id'])) {
                    $incorrect += 1;
                    continue;
                }
    
                $questionId = $answer['id'];
                $question = Question::find($answer['id']);
    
                if (!$question) {
                    $incorrect += 1;
                    continue;
                }
    
                $isCorrect = false;
                if (isset($answer['answer'])) {
                    $userAnswer = $answer['answer'];
                    if ($examResult->point_type != "manual") {
                        $totalMarks += $question->default_marks;
                    }
    
                    // Check the answer based on question type
                    switch ($question->type) {
                        case 'MSA':
                            $isCorrect = $question->answer == $userAnswer;
                            break;
                        case 'MMA':
                        case 'FIB':
                        case 'ORD':
                        case 'EMQ':
                            $correctAnswers = json_decode($question->answer, true);
                            sort($correctAnswers);
                            sort($userAnswer);
                            $isCorrect = $userAnswer == $correctAnswers;
                            break;
                        case 'TOF':
                            $isCorrect = $userAnswer == $question->answer;
                            break;
                            case 'SAQ':
                            // Check if userAnswer is an array
                            if (is_array($userAnswer)) {
                                $isCorrect = false;
                                foreach ($userAnswer as $singleAnswer) {
                                    $sanitizedUserAnswer = strtolower(trim(strip_tags($singleAnswer)));
                                    if (in_array($sanitizedUserAnswer, array_map('strtolower', array_map('trim', json_decode($question->options, true))))) {
                                        $isCorrect = true;
                                        break; // Exit loop if any answer matches
                                    }
                                }
                            } else {
                                // Process as a single answer if not an array
                                $sanitizedUserAnswer = strtolower(trim(strip_tags($userAnswer)));
                                $isCorrect = in_array($sanitizedUserAnswer, array_map('strtolower', array_map('trim', json_decode($question->options, true))));
                            }
                            break;
                        case 'MTF':
                            $correctAnswers = json_decode($question->answer, true);
                            $isCorrect = !array_diff_assoc($correctAnswers, $userAnswer);
                            break;
                    }
    
                    // Update score and track incorrect questions
                    if ($isCorrect) {
                        $score += $examResult->point_type == "manual" ? $examResult->point : $question->default_marks;
                        $correctAnswer += 1;
                    } else {
                        $wrongQuestionIds[] = $questionId;
                        $incorrect += 1;
                        $incorrectMarks += $question->default_marks ?? 0;
                    }
                } else {
                    $wrongQuestionIds[] = $questionId;
                    $incorrect += 1;
                    $incorrectMarks += $question->default_marks ?? 0;
                }
            }
    
            // Apply negative marking
            if ($examResult->negative_marking == 1) {
                $negativeMarks = $examResult->negative_marking_type == "fixed" 
                    ? $examResult->negative_marking_value * $incorrect 
                    : ($examResult->negative_marking_value / 100) * $incorrectMarks;
                $score = max(0, $score - $negativeMarks);
            }
    
            // Calculate percentage and determine pass/fail status
            $studentPercentage = ($totalMarks > 0) ? ($score / $totalMarks) * 100 : 0;
            $studentStatus = ($studentPercentage >= $examResult->pass_percentage) ? 'PASS' : 'FAIL';
    
            // Update the exam result
            $examResult->update([
                'status' => 'complete',
                'updated_at' => now(),
                'score' => $score,
                'incorrect_answer' => $incorrect,
                'correct_answer' => $correctAnswer,
                'student_percentage' => round($studentPercentage, 2),
                'wrong_question_ids' => json_encode($wrongQuestionIds, true),
            ]);
        }
    }
    
}
