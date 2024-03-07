<!DOCTYPE html>
<html lang="en">
   <head>
      <title>GFG- Store Data</title>
   </head>
   <body>
      <center>
         <h1>Storing Form data in Database</h1>
         <form action="insert.php" method="post">
             
<p>
               <label for="projectName">Project Name:</label>
               <input type="text" name="project_name" id="projectName">
            </p>
 
             
<p>
               <label for="boringId">Boring ID:</label>
               <input type="text" name="boring_id" id="boringId">
            </p>
 
             
<p>
               <label for="sampleNumber">Sample Number:</label>
               <input type="text" name="sample_number" id="sampleNumber">
            </p>
 
             
<p>
               <label for="LDepth">Depth:</label>
               <input type="text" name="depth" id="LDepth">
            </p>
             
<p>
               <label for="bag/tubeNumber">Bag/Tube Number:</label>
               <input type="text" name="bag/tube_number" id="bag/tubeNumber">
            </p>

 <p>
               <label for="testName">Test Name:</label>
               <input type="text" name="test_name" id="testName">
            </p>

<p>
               <label for="LNotes">Notes:</label>
               <input type="text" name="notes" id="LNotes">
            </p>    
            
<p>

                    <b>Progress</b>     
                    <p>
                    <p>
                        <label for="notstarted"> Not Started</label><br>
                        <input name = 'Progress' type = "radio" id = 'notstarted' value = 'notstarted'required>
                    </p>
                    <p>
                        <label for="inprogress"> In Progress</label><br>
                        <input name = 'Progress' type = "radio" id = 'inprogress' value = 'inprogress'required> 
                    </p>
                    <p>
                        <label for="completed"> Completed</label><br>
                        <input name = 'Progress' type = "radio" id = 'completed' value = 'completed'required> 
                    </p>
                    </p>
            </p>
 
            <input type="submit" value="Submit">
         </form>
      </center>
   </body>
</html>