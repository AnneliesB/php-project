<?php

if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 1048600)) // less than 1MB
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    header("Location: step4.php");
/*
    echo "Upload: " . $_FILES["file"]["name"] . "<br />";
    echo "Type: " . $_FILES["file"]["type"] . "<br />";
    echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

*/
    if (file_exists("upload/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "upload/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
      }
    }
  }
else
  {
  echo "Invalid file";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

<form action="upload.php" method="post" enctype="multipart/form-data">
<p>
<label for="file0">Filename: </label>
<input name="file[]" type="file" id="file0" size="20" />
</p>
<p>
<label for="file0desc">Description: </label>
<textarea rows="10" cols="30" id="file0desc" class="textarea"></textarea>
</p>
<p>
<label for="file1desc">Description: </label>
<textarea rows="10" cols="30" id="file1desc" class="textarea"></textarea>
</p>
<p>
<input id="submit" type="submit" name="submit" value="Continue to Step 4" />
</p>
</form>

</body>
</html>