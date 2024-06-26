<?php
session_start();
@include("config.php");
@include("checkSession.php");

?>

<!DOCTYPE html>
<html>
<header>
  <div class="text-box">
    <h1 id="title">QR Code Generator</h1><hr>
  </div>
</header>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="initial-scale=1, width=device-width" />

  <link rel="stylesheet" href="./global.css" />
  <link rel="stylesheet" href="./QRGen.css" />
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap"
  />
  <link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Lohit Devanagari:wght@400&display=swap"
  />
</head>

    <div class="container">
      <form action="insert.php" method="POST" id="results">
        <div class="labels">
          <label id="number-label" for="IGL"> IGL</label></div>
        <div class="input-tab">
          <input class="input-field" type="number" id="IGL" name="igl"placeholder="1000" required min="1000"max="9999"></div>
         <div class="labels">
          <label id="name-label" for="projectName"> Project name</label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="projectName" name="project_name" placeholder="Enter the project name." required autofocus></div>
        <div class="labels">
          <label id="number-label" for="boringId"> Boring ID</label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="boringId" name="boring_id"placeholder="1500..." required></div>
        <div class="labels">
          <label id="name-label" for="sampleNumber"> Sample number</label></div>
        <div class="input-tab">              
          <input class="input-field" type="text" id="sampleNumber" name="sample_number" placeholder="Enter the sample number." required autofocus></div>
        <div class="labels">
          <label id="name-label" for="LDepth"> Depth</label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="LDepth" name="depth" placeholder="Enter the depth." required autofocus></div>
        <div class="labels">
          <label id="name-label" for="bag/tubeNumber"> Bag/Tube number</label></div>
        <div class="input-tab">
          <input class="input-field" type="number" id="bag/tubeNumber" name="bag_tube_number" placeholder="Enter the bag/tube number." required autofocus></div>
        <div class="labels">
          <label id="name-label" for="testName"> Test name</label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="testName" name="test_name" placeholder="Enter the test name." required autofocus></div>
        <div class="labels">
          <label id="name-label" for="LLocation"> Storage location</label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="LLocation" name="location"placeholder="..." required></div>
        <div class="labels">
          <label id="name-label" for="LNotes"> Notes </label></div>
        <div class="input-tab">
          <input class="input-field" type="text" id="LNotes" name="notes" placeholder="Enter any notes." required autofocus></div>
        
        
        <div class="labels">
          <label>Progress?</label></div>
        <div class="input-tab">
            <input type="radio" name="progress" value="Not Started" class="radio-button" required>Not started<br>
            <input type="radio" name="progress" value="In Progress" class="radio-button" required>In Progress<br>
            <input type="radio" name="progress" value="Completed" class="radio-button" required>Completed<br>
        </div>
        
        <div class="btn">
          <button id="submit" type="submit">Submit</button>
        </div>
      </form>
    </div>
    <script>
      const inputField = document.getElementById('IGL');

       inputField.addEventListener('blur', function (e) {
            const value = e.target.value;

            // If the input is shorter than 4 digits, show a warning (or handle as needed)
            if (value.length !== 4) {
                alert('The number must be exactly 4 digits long.');
            }
        });
    </script>
</footer>
</html>
