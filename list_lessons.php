<?php
$files = scandir(".");
$lessons = [];
foreach ($files as $file)
{
    if (strpos($file, "_pairs.txt") !== false)
    {
        $lessons[] = str_replace("_pairs.txt","",$file);
    }
}
echo '<ul>';
foreach ($lessons as $lesson)
{
    echo '<li><a href="ear_trainer.php?' . 'lesson=' . $lesson . '&score=0&line=0">' . $lesson . '</a></li>';
}
echo '</ul>';

?>
