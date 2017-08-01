<?php
    $dir = "sounds";
    $mp3_files = [];
    $lesson = $_GET["lesson"];
	$postlen = count($_POST);
    $lesson_file = $lesson . '_pairs.txt';
    $score = $_POST["current_score"];
    $line = $_POST["line"];
    if ($postlen < 2)
    {
		$score = 0;
		$line = 0;
	}
    $lines = file($lesson_file);
    $lesson_length = count($lines);
    $mp3_files[0] = explode(", ",$lines[$line])[0] . ".mp3";
    $mp3_files[1] = explode(", ",$lines[$line])[1] . ".mp3";
    $wav_files[0] = explode(", ",$lines[$line])[0] . ".wav";
    $wav_files[1] = explode(", ",$lines[$line])[1] . ".wav";

    //Pick a random valid index of $mp3_files
    $random_index = rand(0,count($mp3_files)-1);
?>

<html>
	<link rel="stylesheet" type="text/css" href="stylesheet.css"/>

    <script>
    var trycount = 0;
    function clicked_answer(button)
    {
        var php_random_index = "<?php echo $random_index; ?>";
        var lesson_length = parseInt("<?php echo $lesson_length; ?>");
        var lesson = "<?php echo $lesson; ?>";
        var score = parseInt("<?php echo $score; ?>");
        var line = parseInt("<?php echo $line; ?>");
	document.getElementById("quizscore").innerHTML="QUIZ - Score: " + score.toString() + "/" + (line + 1).toString();

        if ((button == "button0" &&  php_random_index == 0) || (button == "button1" &&  php_random_index == 1))
            {
                if (trycount == 0)
                {
                    score += 1;
                }
                if (line + 1 < lesson_length)
                {
                    document.getElementById("correct").innerHTML="<img src='images/molumen_green_square_submit_icon.png' style='float:center'>Correct!";
                    document.getElementById("quizscore").innerHTML="QUIZ - Score: " + score.toString() + "/" + (line + 1).toString();
					line += 1;
                    document.getElementById("line").value=line;
                    document.getElementById("current_score").value=score;
                    document.getElementById("form").action="ear_trainer.php?lesson=" + lesson;
                    document.getElementById("form").style.visibility="visible";
                }
                else
                {
					document.getElementById("quizscore").innerHTML="QUIZ - Score: " + score.toString() + "/" + (line + 1).toString();
                    document.getElementById("correct").innerHTML="<img src='images/molumen_green_square_submit_icon.png' style='float:center'>Correct! You are finished.";
                    document.getElementById("form").action="index.php";
                    document.getElementById("submit_button").value="Home";
                    document.getElementById("form").style.visibility="visible";
                }
            }
            else
            {
                document.getElementById("correct").innerHTML="<img src='images/molumen_red_square_error_warning_icon.png' style='float:center'>Wrong!";
                trycount += 1;
            }
    }
    </script>
    <title><?php echo $lesson; ?></title>
    <body>
	<?php include_once("analyticstracking.php") ?>
	<div id="header">
		<img src="images/paro_AL_LISTEN_.png"/> Welcome to Ear Trainer
	</div>
	<div id="lessons">
		<p><strong>Lessons:</strong></p>
		<?php include "list_lessons.php" ?>
	</div>

	<div class="main">
	        LISTEN
	        <br/>
	        <table border="1">
	            <tr>
        	        <td><?= substr($mp3_files[0],0, -4) ?></td>
                	<td> <?= substr($mp3_files[1],0, -4) ?></td></tr>
	            <tr>
	                <td> <audio controls><source src="<?= $dir . '/' . $mp3_files[0] ?>" type="audio/mpeg"><source src="<?= $dir . '/' . $wav_files[0] ?>" type="audio/wav"></audio> </td>
	                <td> <audio controls><source src="<?= $dir . '/' . $mp3_files[1] ?>" type="audio/mpeg"><source src="<?= $dir . '/' . $wav_files[1] ?>" type="audio/wav"></audio> </td></tr>
	        </table>

	        <p id="quizscore">QUIZ - Score: <?= $score ?>/ <?= $line + 1 ?> </p>
	        <audio controls><source src="<?= $dir . '/' . $mp3_files[$random_index] ?>" type="audio/mpeg"><source src="<?= $dir . '/' . $wav_files[$random_index] ?>" type="audio/wav"></audio>
	
	        <table border="1">
	            <tr>
	                <td><button name="button0" onclick="clicked_answer(this.name)" ><?= substr($mp3_files[0],0, -4) ?></button></td>
	                <td><button name="button1" onclick="clicked_answer(this.name)" ><?= substr($mp3_files[1],0, -4) ?></button></td>
	            </tr>
	        </table>

	        <span id="correct"></span>
	        <!-- <a id="next"></a> -->
	        <form name="form" id="form" action=<?="ear_trainer.php?lesson=" . $lesson ?> method="POST" style="visibility:hidden">
	        <input type="hidden" name="current_score" id="current_score" value="<?= $score?>" />
	        <input type="hidden" name="line" id="line" value="<?= $line + 1 ?>" />
	        <input type="submit" name="submit_button" id="submit_button" value="Next"/>
	        </form>
        
	</div>
        <p>If you cannot hear the sound, your computer or browser doesn't support the sound format.</p>
        <p>Or, you have your speakers turned off :)</p>

    </body>
</html>


