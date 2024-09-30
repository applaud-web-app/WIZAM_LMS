<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FileManager;
use Illuminate\Support\Facades\File;
use Pion\Laravel\ChunkUpload\Exceptions\UploadFailedException;
use Illuminate\Http\UploadedFile;
use Pion\Laravel\ChunkUpload\Exceptions\UploadMissingFileException;
use Pion\Laravel\ChunkUpload\Handler\AbstractHandler;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    public function fileManager(){
        $fileManager = FileManager::orderBy('id', 'ASC');
        // Fetch parent directories
        $parentData = $fileManager->where('parent_node', 0)->get();
        // Fetch parent folders
        $parentDirectory = FileManager::where(['parent_node' => 0, 'type' => 'folder'])->orderBy('id', 'ASC')->get();
        return view('fileManager.view-files',compact('parentDirectory','parentData'));
    }

    // public function addFolder(Request $request){
    //     $request->validate([
    //         'eq' => 'required',
    //         'folder_name'=> 'required',
    //     ]);

    //     try {
    //         $data = decrypturl($request->eq);
    //         $directory_id = $data['id'];

    //         // THEN  IT"S A PARENT DEIRECTRY
    //         if($directory_id == 0){
    //             $fileManager = FileManager::create([
    //                 'node_name'=>$request->folder_name,
    //                 'parent_node'=>$directory_id,
    //                 'type'=>'folder'
    //             ]);
    //             return redirect()->back()->with('success','Folder Created  Successfully');
    //         }

    //         $parentDirectory = FileManager::where(['id' => $directory_id, 'type' => 'directory'])->first();
    //         if ($parentDirectory) {
    //             // Fetch child directories
    //             $childDirectory = FileManager::create([
    //                 'node_name'=>$request->folder_name,
    //                 'parent_node'=>$directory_id,
    //                 'type'=>'folder'
    //             ]);

    //             return redirect()->back()->with('success','Folder Created  Successfully');
    //         }
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with('error','Something Went Wrong : '.$th->getMessage());
    //     }

    //     return redirect()->back()->with('error','Something Went Wrong!!');
    // }

    public function addFolder(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'eq' => 'required',
            'folder_name' => 'required',
        ]);

        try {
            $data = decrypturl($request->eq);
            $directory_id = $data['id'];

            // Create a folder in the root directory (parent directory)
            if ($directory_id == 0) {
                $fileManager = FileManager::create([
                    'node_name' => $request->folder_name,
                    'parent_node' => $directory_id,
                    'type' => 'folder'
                ]);

                return response()->json(['success' => true, 'message' => 'Folder created successfully']);
            }

            // Check for a valid parent directory
            $parentDirectory = FileManager::where(['id' => $directory_id, 'type' => 'directory'])->first();
            if ($parentDirectory) {
                // Create a child directory under the parent
                $childDirectory = FileManager::create([
                    'node_name' => $request->folder_name,
                    'parent_node' => $directory_id,
                    'type' => 'folder'
                ]);

                return response()->json(['success' => true, 'message' => 'Folder created successfully']);
            }

            // If the parent directory is not found
            return response()->json(['success' => false, 'message' => 'Parent directory not found.'], 404);
            
        } catch (\Throwable $th) {
            // Handle errors
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $th->getMessage()], 500);
        }
    }
    
    // public function saveDirectoryMedia(Request $request) {
    //     try {
    //         // Validate incoming request
    //         $request->validate([
    //             'eq' => 'required',
    //             'fileNames' => 'required|array', // Ensure fileNames is an array
    //             'fileNames.*' => 'string' // Each file name should be a string
    //         ]);
    
    //         // Decrypt the request parameter
    //         $data = decrypturl($request->eq);
    //         $directory_id = $data['id'];

    //         // FOR PARENT FILE
    //         if($directory_id == 0){
    //             $createdFiles = []; // To store successfully created file names
    //             $errors = []; // To collect errors if any
    
    //             // Loop through the file names to determine type and store them
    //             foreach ($request->fileNames as $fileName) {
    //                 // Get the file extension and base name
    //                 $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    //                 $baseName = pathinfo($fileName, PATHINFO_FILENAME); // Get the name without the extension
    
    //                 // Determine the type based on the extension
    //                 // $type = in_array(strtolower($extension), ['mp4', 'avi', 'mov', 'mkv']) ? 'video' : 'document';
    
    //                 // Check for existing file names without extension
    //                 $existingFile = FileManager::where('node_name', $baseName)
    //                     ->where('parent_node', $directory_id)
    //                     ->first();
    
    //                 if ($existingFile) {
    //                     $errors[] = "File name '$baseName' already exists in this directory.";
    //                 } else {
    //                     // Create the child directory for each file with the name without extension
    //                     $data =  FileManager::create([
    //                         'node_name' => $baseName, // Store the base name without extension
    //                         'parent_node' => $directory_id,
    //                         'source' => $fileName,
    //                         'type' => "media"
    //                     ]);
    //                     $createdFiles[] = $data; // Track successfully created file names
    //                 }
    //             }

    //             // Prepare the response
    //             if (!empty($errors)) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'errors' => $errors,
    //                 ], 400); // Return errors with a 400 status
    //             }
    
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Files uploaded successfully',
    //                 'created_files' => $createdFiles
    //             ]);
    //         }
    
    //         // Fetch parent directory
    //         $parentDirectory = FileManager::find($directory_id);
    //         if ($parentDirectory) {
    //             $createdFiles = []; // To store successfully created file names
    //             $errors = []; // To collect errors if any
    
    //             // Loop through the file names to determine type and store them
    //             foreach ($request->fileNames as $fileName) {
    //                 // Get the file extension and base name
    //                 $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    //                 $baseName = pathinfo($fileName, PATHINFO_FILENAME); // Get the name without the extension
    
    //                 // Determine the type based on the extension
    //                 // $type = in_array(strtolower($extension), ['mp4', 'avi', 'mov', 'mkv']) ? 'video' : 'document';
    
    //                 // Check for existing file names without extension
    //                 $existingFile = FileManager::where('node_name', $baseName)
    //                     ->where('parent_node', $directory_id)
    //                     ->first();
    
    //                 if ($existingFile) {
    //                     $errors[] = "File name '$baseName' already exists in this directory.";
    //                 } else {
    //                     // Create the child directory for each file with the name without extension
    //                     $data =  FileManager::create([
    //                         'node_name' => $baseName, // Store the base name without extension
    //                         'parent_node' => $directory_id,
    //                         'source' => $fileName,
    //                         'type' => "media"
    //                     ]);
    //                     $createdFiles[] = $data; // Track successfully created file names
    //                 }
    //             }
    
    //             // Prepare the response
    //             if (!empty($errors)) {
    //                 return response()->json([
    //                     'status' => 'error',
    //                     'errors' => $errors,
    //                 ], 400); // Return errors with a 400 status
    //             }
    
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Files uploaded successfully',
    //                 'created_files' => $createdFiles
    //             ]);
    //         }
    //     } catch (ValidationException $e) {
    //         // Return a custom error response for validation failures
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'File not safe due to unexpected error: ' . $e->getMessage(),
    //             'errors' => $e->validator->errors(), // Include validation errors
    //         ], 422); // Return a 422 status for unprocessable entity
    //     } catch (\Throwable $th) {
    //         // Handle any other unexpected errors
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Something went wrong: ' . $th->getMessage()
    //         ], 500); // Return error with a 500 status
    //     }
    
    //     return response()->json([
    //         'status' => 'error',
    //         'message' => 'Something went wrong!!'
    //     ], 500);
    // }

    public function saveDirectoryMedia(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'eq' => 'required',
                'fileNames' => 'required|array', // Ensure fileNames is an array
                'fileNames.*.image' => 'required|string', // Each image URL should be a string
                'fileNames.*.original' => 'required|string', // Each original name should be a string
            ]);

            // Decrypt the request parameter
            $data = decrypturl($request->eq);
            $directory_id = $data['id'];

            // Create files in the specified directory
            return response()->json($this->createFiles($request->fileNames, $directory_id));
            
        } catch (ValidationException $e) {
            // Return a custom error response for validation failures
            return response()->json([
                'status' => 'error',
                'message' => 'Validation error: ' . $e->getMessage(),
                'errors' => $e->validator->errors(), // Include validation errors
            ], 422); // Return a 422 status for unprocessable entity

        } catch (\Throwable $th) {
            // Handle any other unexpected errors
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $th->getMessage()
            ], 500); // Return error with a 500 status
        }
    }


    private function createFiles(array $fileNames, int $directory_id)
    {
        $createdFiles = []; // To store successfully created file names
        $errors = []; // To collect errors if any
    
        // Loop through the file names to create files
        foreach ($fileNames as $fileData) {
            $originalName = $fileData['original']; // Original name from the response
            $baseName = pathinfo($originalName, PATHINFO_FILENAME); // Get the base name without extension
    
            // Check for existing file names and modify if necessary
            $existingFile = FileManager::where('node_name', $baseName)
                ->where('parent_node', $directory_id)
                ->first();
    
            // Handle duplicates
            if ($existingFile) {
                // Append (1), (2), etc. to the base name until a unique name is found
                $counter = 1;
                while ($existingFile) {
                    $newBaseName = $baseName . " ($counter)";
                    $existingFile = FileManager::where('node_name', $newBaseName)
                        ->where('parent_node', $directory_id)
                        ->first();
                    $counter++;
                }
                $baseName = $newBaseName; // Update baseName to the new unique name
            }
    
            // Create the file entry
            $data = FileManager::create([
                'node_name' => $baseName, // Store the unique base name without extension
                'parent_node' => $directory_id,
                'source' => $fileData['image'], // Use the image source
                'type' => "media"
            ]);
            $createdFiles[] = $data; // Track successfully created file names
        }
    
        // Prepare the response
        if (!empty($errors)) {
            return [
                'status' => 'error',
                'errors' => $errors,
            ]; // Return errors with an appropriate status
        }
    
        return [
            'status' => 'success',
            'message' => 'Files uploaded successfully',
            'created_files' => $createdFiles
        ];
    }
    


    public function addDirectory(Request $request)
    {
        // Validate incoming request data
        try {
            $request->validate([
                'directory_name' => 'required|string|max:255',
            ]);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage(),
                'errors' => $e->errors() // Return validation errors
            ], 422); // HTTP status code 422 Unprocessable Entity
        }

        try {
            $directry = FileManager::where(['type'=>'directory','node_name' => $request->input('directory_name')])->first();
            if($directry){
                return response()->json([
                    'success' => false,
                    'message' => 'The directory name has already been taken for a directory.'
                ], 500); // HTTP status code 500 Internal Server Error
            }


            // Create a new directory
            $directory = FileManager::create([
                'node_name' => $request->input('directory_name'), // Get the directory name from the request
                'parent_node' => 0, // You can adjust this based on your structure
                'type' => 'directory', // Set type as 'directory'
                'source' => null, // Set to null or adjust based on your requirements
            ]);

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Directory added successfully!',
                'data' => $directory // Optional: return the created directory
            ], 201); // HTTP status code 201 Created
        } catch (QueryException $e) {
            // Handle database-related errors
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred: ' . $e->getMessage()
            ], 500); // HTTP status code 500 Internal Server Error
        } catch (\Exception $e) {
            // Handle any other type of error
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500); // HTTP status code 500 Internal Server Error
        }
    }

    // FETCH FILEDATA 
    // public function fetchDirectoryData(Request $request){
    //     // Validate incoming request data
    //     try {
    //         $request->validate([
    //             'eq' => 'required'
    //         ]);
    //     } catch (ValidationException $e) {
    //         // Handle validation errors
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation Error: ' . $e->getMessage()
    //         ], 422); // HTTP status code 422 Unprocessable Entity
    //     }

    //     try {
    //         $data = decrypturl($request->eq);
    //         $directory_id = $data['id'];

    //         // Fetch the parent directory
    //         $parentDirectory = FileManager::where(['id' => $directory_id, 'type' => 'directory'])->first();

    //         if ($parentDirectory) {
    //             // Fetch child directories
    //             $childDirectory = FileManager::where(['parent_node' => $directory_id])->get();
    //             $parms = 'id=' . $parentDirectory->id;
    //             $del_url = route('delete-directory');
    //             $upload_url = route('save-directory-media');
    //             $add_folder = route('add-folder');
    //             // Return a success response
    //             return response()->json([
    //                 'success' => true,
    //                 'data' => $childDirectory,
    //                 'deleteUrl'=> encrypturl($del_url,$parms),
    //                 'uploadUrl'=> encrypturl($upload_url,$parms),
    //                 'addFolderUrl'=> encrypturl($add_folder,$parms),
    //             ], 200); // HTTP status code 200 OK
    //         }

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Unknown Directory Found'
    //         ], 404); // HTTP status code 404 Not Found
    //     } catch (\Throwable $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred: ' . $e->getMessage()
    //         ], 500); // HTTP status code 500 Internal Server Error
    //     }
        
    // }

    // public function deleteDirectory(Request $request){
    //     try {
    //         $request->validate([
    //             'eq' => 'required'
    //         ]);
    //     } catch (ValidationException $e) {
    //         // Handle validation errors
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation Error: ' . $e->getMessage()
    //         ], 422); // HTTP status code 422 Unprocessable Entity
    //     }

    //     try {
    //         $data = decrypturl($request->eq);
    //         $directory_id = $data['id'];

    //         $media = FileManager::where('id',$directory_id)->delete();
    //         if($media->type == "Folder"){
    //             // MAKE DELETE ALL CHILD SUB SCHILD AND SO ONE
    //             if ($parentDirectory) {
    //                 // Fetch child directories
    //                 $childDirectory = FileManager::where(['parent_node' => $directory_id])->delete();
    //                 // Return a success response
    //                 return response()->json([
    //                     'success' => true,
    //                     'message' => 'Directory Removed Successfully'
    //                 ], 200); // HTTP status code 200 OK
    //             }
    //         }

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'File Remove Successfully'
    //         ], 404); // HTTP status code 404 Not Found

    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred: ' . $e->getMessage()
    //         ], 500); // HTTP status code 500 Internal Server Error
    //     }
    // }

    public function fetchDirectoryData(Request $request)
    {
        $parent_id = $request->input('parent_id', 0); // Default to 0 if not provided
    
        // Fetch data from your FileManager model based on the parent_id
        $parentData = FileManager::where('parent_node', $parent_id)->get();
    
        // Separate folders and files
        $folders = $parentData->filter(fn($item) => $item->type === 'folder');
        $files = $parentData->filter(fn($item) => $item->type !== 'folder');
    
        return response()->json([
            'success' => true,
            'folders' => $folders,
            'files' => $files
        ]);
    }
    

    public function deleteDirectory(Request $request) {
        try {
            $request->validate([
                'eq' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error: ' . $e->getMessage()
            ], 422); 
        }
    
        try {
            $data = decrypturl($request->eq);
            $directory_id = $data['id'];
    
            $media = FileManager::find($directory_id);
    
            if (!$media) {
                return response()->json([
                    'success' => false,
                    'message' => 'Media not found'
                ], 404);
            }
    
            // If it's a folder, delete all child items
            if ($media->type == "Folder") {
                $this->deleteChildFiles($directory_id);
    
                // Delete the main folder entry
                $media->delete();
            } else {
                // It's a file, delete it directly
                $filePath = 'filemanager/' . $media->source;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
    
                $media->delete();
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Permanently Deleted successfully'
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $th->getMessage()
            ], 500); 
        }
    }
    
    private function deleteChildFiles($directory_id) {
        // Get all child items recursively
        $childItems = FileManager::where('parent_node', $directory_id)->get();
    
        foreach ($childItems as $child) {
            if ($child->type == "Folder") {
                $this->deleteChildFiles($child->id);
            } else {
                $filePath = 'filemanager/' . $child->source;
                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }
    
            // Delete the record from the database
            $child->delete();
        }
    }
    


   
    // protected function createFilename(UploadedFile $file) {
    //     $extension = $file->getClientOriginalExtension();
    //     $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); 

    //     $filename = "Wizam".rand(1000, 9999)."-".strtolower($filename);

    //     //here you can manipulate with file name e.g. HASHED
    //     return $filename.".".$extension;
    // }

    // // Upload MEDIA
    // public function uploadMedia(Request $request) {
    //     $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
    
    //     // Check if the upload is successful
    //     if ($receiver->isUploaded() === false) {
    //         throw new UploadMissingFileException();
    //     }
    
    //     // Receive the file
    //     $save = $receiver->receive();
    
    //     // Check if the upload has finished
    //     if ($save->isFinished()) {
    //         return $this->saveFile($save->getFile(), $request);
    //     }
    
    //     // We are in chunk mode, let's send the current progress
    //     $handler = $save->handler();
    //     return response()->json([
    //         "done" => $handler->getPercentageDone(),
    //         'status' => true,
    //     ]);
    // }
    
    // protected function saveFile(UploadedFile $file, Request $request) {
    //     $fileName = $this->createFilename($file);
    
    //     // Determine if it's a video or another type of file
    //     $mimeType = $file->getMimeType();
    //     $isVideo = strpos($mimeType, 'video') !== false;
    
    //     // Set the file path based on the file type
    //     $filePath = $isVideo ? "filemanager/videos/" : "filemanager/doc/";
    //     $finalPath = storage_path("app/" . $filePath);
    
    //     // Move the uploaded file to the final destination
    //     $file->move($finalPath, $fileName);
    
    //     return response()->json([
    //         'path' => $filePath,
    //         'name' => $fileName,
    //         'mime_type' => $mimeType
    //     ]);
    // }

    // public function removeMedia(Request $request) {
    //     $files = $request->filename; // Assuming you are passing multiple filenames in an array
    
    //     if (!is_array($files)) {
    //         $files = [$files]; // Ensure it's an array
    //     }
    
    //     $deletedFiles = [];
    //     $failedFiles = [];
    
    //     foreach ($files as $file) {
    //         // Get the extension to determine file type
    //         $extension = pathinfo($file, PATHINFO_EXTENSION);
    //         $isVideo = in_array(strtolower($extension), ['mp4', 'avi', 'mov', 'mkv']); // Add more video extensions if needed
    
    //         // Set the directory based on the file type
    //         $finalPath = storage_path("app/");
    
    //         // Construct the full file path
    //         $fullFilePath = $finalPath . $file;
    
    //         // Ensure $fullFilePath points to a file and not a directory
    //         if (is_file($fullFilePath)) {
    //             // Check if the file exists and attempt to delete it
    //             if (unlink($fullFilePath)) {
    //                 $deletedFiles[] = $file;
    //             } else {
    //                 $failedFiles[] = $file;
    //             }
    //         } else {
    //             $failedFiles[] = $file; // Add to failed if it's not a file
    //         }
    //     }
    
    //     return response()->json([
    //         'status' => count($failedFiles) === 0 ? 'ok' : 'error',
    //         'deleted_files' => $deletedFiles,
    //         'failed_files' => $failedFiles,
    //     ], count($failedFiles) === 0 ? 200 : 403);
    // }

    protected function createFilename(UploadedFile $file) {
        $extension = $file->getClientOriginalExtension();
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Get the original filename without extension
    
        // Generate a unique filename with a timestamp and random number
        $uniqueFilename = "Wizam" . time() . rand(1000, 9999) . "-" . uniqid(); // Using time and uniqid for uniqueness
    
        // Return the unique filename with its extension
        return $uniqueFilename . "." . $extension;
    }

    // Upload MEDIA
    public function uploadMedia(Request $request) {
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));

        // Check if the upload is successful
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }

        // Receive the file
        $save = $receiver->receive();

        // Check if the upload has finished
        if ($save->isFinished()) {
            return $this->saveFile($save->getFile(), $request);
        }

        // We are in chunk mode, let's send the current progress
        $handler = $save->handler();
        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true,
        ]);
    }

    protected function saveFile(UploadedFile $file, Request $request) {
        // Get the original filename before generating a new one
        $originalFilename = $file->getClientOriginalName();
        $fileName = $this->createFilename($file);

        // Determine if it's a video or another type of file
        $mimeType = $file->getMimeType();
        $isVideo = strpos($mimeType, 'video') !== false;

        // Set the file path based on the file type
        $filePath = $isVideo ? "videos/" : "doc/";
        $finalPath = storage_path("app/filemanager/" . $filePath);

        // Move the uploaded file to the final destination
        $file->move($finalPath, $fileName);

        // Return both the new filename and the original filename in the response
        return response()->json([
            'path' => $filePath,
            'new_name' => $fileName,
            'original_name' => $originalFilename,
            'mime_type' => $mimeType,
        ]);
    }

    public function removeMedia(Request $request) {
        $files = $request->filename; // Assuming you are passing multiple filenames in an array

        if (!is_array($files)) {
            $files = [$files]; // Ensure it's an array
        }

        $deletedFiles = [];
        $failedFiles = [];

        foreach ($files as $file) {
            // Get the extension to determine file type
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $isVideo = in_array(strtolower($extension), ['mp4', 'avi', 'mov', 'mkv']); // Add more video extensions if needed

            // Set the directory based on the file type
            $finalPath = storage_path("app/");

            // Construct the full file path
            $fullFilePath = $finalPath . $file;

            // Ensure $fullFilePath points to a file and not a directory
            if (is_file($fullFilePath)) {
                // Check if the file exists and attempt to delete it
                if (unlink($fullFilePath)) {
                    $deletedFiles[] = $file;
                } else {
                    $failedFiles[] = $file;
                }
            } else {
                $failedFiles[] = $file; // Add to failed if it's not a file
            }
        }

        return response()->json([
            'status' => count($failedFiles) === 0 ? 'ok' : 'error',
            'deleted_files' => $deletedFiles,
            'failed_files' => $failedFiles,
        ], count($failedFiles) === 0 ? 200 : 403);
    }
    
    
}
