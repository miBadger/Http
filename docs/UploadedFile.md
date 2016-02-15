# UploadedFile

The uploaded file class is used to simplify http file upload operations.

## Example(s)

```php
// Create a new server request.
$serverRequest = new ServerRequest();

// Get stream
$uploadedFiles = $serverRequest->getUploadedFiles();

// Get content
foreach ($uploadedFiles as $uploadedFile) {
	uploadedFile->getStream()->getContents();
}
```
