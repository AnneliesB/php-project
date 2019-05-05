<?php
require_once("bootstrap/bootstrap.php");

//Check if user session is active (Is user logged in?)
if (isset($_SESSION['email'])) {
    //User is logged in, no redirect needed!
} else {
    //User is not logged in, redirect to login.php!
    header("location: login.php");
}

if (!empty($_POST)) {
    // UPLOAD image
    if (isset($_POST['upload'])) {
        // GET image name / filename / description
        $image = Image::getPostId() . $_FILES['image']['name'];
        $imageSaveName = $_FILES['image']['tmp_name'];

        $croppedImage = Image::getPostId() . "cropped-" . $_FILES['image']['name'];
        $description = $_POST['description'];
        $city = $_POST['city'];

        if (Image::checkExtention($image)) {
            // If extention is png or jpeg
            Image::saveImageToDb($image, $croppedImage, $description, $city);
            Image::saveImage($image, $imageSaveName);
            Image::saveCroppedImage($image);
            Image::saveMainColors($image);
            header("location: index.php");
        } else {
            // Else error message
            $error = "You can only upload png or jpg.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/cssgram.css">
    <title>IMSTAGRAM - add post</title>
</head>

<body class="post">
<?php include_once("nav.incl.php"); ?>
<form action="" method="POST" enctype="multipart/form-data">
    <?php if (isset($error)): ?>
        <div class="formError">
            <p>
                <?php echo $error ?>
            </p>
        </div>
    <?php endif; ?>
    <div class="flexbox postPage">
        <h2 formTitle>Add post</h2>


        <div class="formField">
            <label for="image">Upload a picture</label>
            <div class="uploadFileWrapper">
                <input type="file" id="image" name="image" onchange="loadFile(event)">
            </div>

        </div>

        <div class="imageWrapper">
            <figure>
                <img id="output" class="uploadedImage" visibility="hidden"/>
            </figure>
        </div>

        <div class="filters">
            <label for="filters">Select a filter</label>
            <div class="filterContainer">
                <div class="caption">
                    <p>normal</p>
                    <div class="filterButtons">
                        <div class="nofilter">
                            <img class="filterOptions F_nofilter" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>_1977</p>
                    <div class="filterButtons">
                        <div class="_1977">
                            <img class="filterOptions F_1977" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>aden</p>
                    <div class="filterButtons">
                        <div class="aden">
                            <img class="filterOptions F_aden" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>brannan</p>
                    <div class="filterButtons">
                        <div class="brannan">
                            <img class="filterOptions F_brannan" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>brooklyn</p>
                    <div class="filterButtons">
                        <div class="brooklyn">
                            <img class="filterOptions F_brooklyn" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>clarendon</p>
                    <div class="filterButtons">
                        <div class="clarendon">
                            <img class="filterOptions F_clarendon" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>earlybird</p>
                    <div class="filterButtons">
                        <div class="earlybird">
                            <img class="filterOptions F_earlybird" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>gingham</p>
                    <div class="filterButtons">
                        <div class="gingham">
                            <img class="filterOptions F_gingham" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>hudson</p>
                    <div class="filterButtons">
                        <div class="hudson">
                            <img class="filterOptions F_hudson" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>inkwell</p>
                    <div class="filterButtons">
                        <div class="inkwell">
                            <img class="filterOptions F_inkwell" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>kelvin</p>
                    <div class="filterButtons">
                        <div class="kelvin">
                            <img class="filterOptions F_kelvin" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>lark</p>
                    <div class="filterButtons">
                        <div class="lark">
                            <img class="filterOptions F_lark" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>lofi</p>
                    <div class="filterButtons">
                        <div class="lofi">
                            <img class="filterOptions F_lofi" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>maven</p>
                    <div class="filterButtons">
                        <div class="maven">
                            <img class="filterOptions F_maven" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>mayfair</p>
                    <div class="filterButtons">
                        <div class="mayfair">
                            <img class="filterOptions F_mayfair" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>moon</p>
                    <div class="filterButtons">
                        <div class="moon">
                            <img class="filterOptions F_moon" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>nashville</p>
                    <div class="filterButtons">
                        <div class="nashville">
                            <img class="filterOptions F_nashville" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>perpetua</p>
                    <div class="filterButtons">
                        <div class="perpetua">
                            <img class="filterOptions F_perpetua" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>reyes</p>
                    <div class="filterButtons">
                        <div class="reyes">
                            <img class="filterOptions F_reyes" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>rise</p>
                    <div class="filterButtons">
                        <div class="rise">
                            <img class="filterOptions F_rise" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>slumber</p>
                    <div class="filterButtons">
                        <div class="slumber">
                            <img class="filterOptions F_slumber" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>stinson</p>
                    <div class="filterButtons">
                        <div class="stinson">
                            <img class="filterOptions F_stinson" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>toaster</p>
                    <div class="filterButtons">
                        <div class="toaster">
                            <img class="filterOptions F_toaster" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>valencia</p>
                    <div class="filterButtons">
                        <div class="valencia">
                            <img class="filterOptions F_valencia" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>walden</p>
                    <div class="filterButtons">
                        <div class="walden">
                            <img class="filterOptions F_walden" visibility="hidden"/>
                        </div>
                    </div>
                </div>
                <div class="caption">
                    <p>willow</p>
                    <div class="filterButtons">
                        <div class="willow">
                            <img class="filterOptions F_willow" visibility="hidden"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="formField">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="10"> </textarea>
        </div>

        <div class="hidden">
            <label for="city">City</label>
            <input type="text" name="city" id="city">
        </div>


        <div class="formField">
            <input type="submit" value="Post" name="upload" class="btn btnPrimary btnPost">
        </div>
    </div>
</form>
<script src="js/postLocation.js"></script>
<script src="js/navigation.js"></script>
<script src="js/showUploadedImage.js"></script>
<script src="js/addFilter.js"></script>
</body>
</html>