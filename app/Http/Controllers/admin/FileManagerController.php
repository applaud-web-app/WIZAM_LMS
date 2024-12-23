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
use Illuminate\Support\Facades\Auth;

class FileManagerController extends Controller
{
    public function fileManager(){

        if (!Auth()->user()->can('file-manager')) { // Assuming 'file-manager' is the required permission
            return redirect()->route('admin-dashboard')->with('error', 'You do not have permission to this page.');
        }
        
        $fileManager = FileManager::orderBy('id', 'ASC');
        $parentData = $fileManager->where('parent_node', 0)->get();
        $parentDirectory = FileManager::where(['parent_node' => 0, 'type' => 'folder'])->orderBy('id', 'ASC')->get();
        return view('fileManager.view-files',compact('parentDirectory','parentData'));
    }

    public function addFolder(Request $request)
    {
        $request->validate([
            'eq' => 'required',
            'folder_name' => 'required',
        ]);

        try {
            $data = decrypturl($request->eq);
            $directory_id = $data['id'];

            if ($directory_id == 0) {
                $fileManager = FileManager::create([
                    'node_name' => $request->folder_name,
                    'parent_node' => $directory_id,
                    'type' => 'folder'
                ]);

                return response()->json(['success' => true, 'message' => 'Folder created successfully']);
            }

            $parentDirectory = FileManager::where(['id' => $directory_id])->first();
            if ($parentDirectory) {
                $childDirectory = FileManager::create([
                    'node_name' => $request->folder_name,
                    'parent_node' => $directory_id,
                    'type' => 'folder'
                ]);

                return response()->json(['success' => true, 'message' => 'Folder created successfully']);
            }

            return response()->json(['success' => false, 'message' => 'Parent directory not found.'], 404);
            
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => 'Something went wrong: ' . $th->getMessage()], 500);
        }
    }

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
        try {
            $request->validate([
                'parent_id' => 'required'
            ]);
    
            $parent_id = $request->input('parent_id', 0);
    
            // Fetch data from your FileManager model based on the parent_id
            $parentData = FileManager::where('parent_node', $parent_id)->get();
    
            // Separate folders and files
            $folders = $parentData->filter(fn($item) => $item->type === 'folder')->values(); // Collect only folder items
            $files = $parentData->filter(fn($item) => $item->type !== 'folder')->values(); // Collect only file items


            // Encrypt URLs for each item
            foreach ($folders as $item) {
                $url = route('fetch-directory-data');
                $parms = 'id=' . $item->id;
                $item->encryptedUrl = encrypturl($url, $parms); // Create an encrypted URL for each item
                $url = route('delete-directory');
                $item->deleteUrl = encrypturl($url, $parms); // Create an encrypted URL for each item
            }

            // Encrypt URLs for each item
            foreach ($files as $item) {
                $url = route('fetch-directory-data');
                $parms = 'id=' . $item->id;
                $item->encryptedUrl = encrypturl($url, $parms); // Create an encrypted URL for each item
                $url = route('delete-directory');
                $item->deleteUrl = encrypturl($url, $parms); // Create an encrypted URL for each item
            }
    
            // Ensure folders and files are always arrays
            $parms = "id=".$parent_id;
            $encryptUrl = encrypturl(route('add-folder'),$parms);
            return response()->json([
                'success' => true,
                'parent_id'=>$parent_id,
                'folders' => $folders->isEmpty() ? [] : $folders, // Return empty array if no folders
                'files' => $files->isEmpty() ? [] : $files, // Return empty array if no files
                'uploadUrl' => encrypturl(route('save-directory-media'),$parms),  // Example URL for uploading files
                'addFolderUrl' => encrypturl(route('add-folder'),$parms),  // Example URL for adding folders
                'deleteUrl' => encrypturl(route('delete-directory'),$parms)
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $th->getMessage()
            ], 500);
        }
    }
    

    public function getParentFolder(Request $request){
        try {

            $parentData = FileManager::where('parent_node', 0)->where('type','folder')->get();

            // Encrypt URLs for each item
            foreach ($parentData as $item) {
                $url = route('fetch-directory-data');
                $parms = 'id=' . $item->id;
                $item->encryptedUrl = encrypturl($url, $parms); // Create an encrypted URL for each item
            }
    
            return response()->json([
                'success' => true,
                'data' => $parentData
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve data',
                'error' => $th->getMessage()
            ], 500);
        }
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
