<?php 

// Function that gets the words from the txt file supplied. 
function getWords($numberOfLetters)
{
    $wordsFound = []; 
    $file = fopen("words.txt", "rb"); 

    while($word = fgets($file))
    {
        if(strlen(trim($word)) == $numberOfLetters) $wordsFound[] = trim($word); 
    }

    fclose($file); 
    return $wordsFound;
}

function replaceAll($guessString, $guessWord, $thisLetter)
{
    foreach(array_keys($guessWord, $thisLetter) as $index)
    {
        $guessString[$index] = $thisLetter; 
    }

    return $guessString; 
}

const LIVES = 6; 
$lives = LIVES; 

// check to make sure that the length entered is greater than 1. 
while(true)
{
    $numberOfLetters = readline("Enter the word length: "); 
    if($numberOfLetters <=1) echo "You must enter a word length greater than 1.\n"; 
    else break; 
}

$words = getWords($numberOfLetters); 
printf("There are %s words with %s letters", count($words), $numberOfLetters); 
echo "\n"; 


// Creating an array with a random word contained within the words list. 
$guessWord = str_split($words[rand(0, count($words) -1 )]);

printf("The word was: %s", implode($guessWord),"\n"); // DEBUG: DELETE AFTERWARDS...

$guessString = array_fill(0, $numberOfLetters, "_"); 

$points = 0; 

while($lives > 0 )
{
    $guessLetter = readline("\nGuess a letter: ");  
    
    if(in_array($guessLetter, $guessWord))
    {
        $guessString = replaceAll($guessString, $guessWord, $guessLetter); 
        $points = $points + 10; 
        echo "You have guessed correctly!\n"; 
    }

    else 
    {
        $lives--; 
        printf("Letter not found! .... Lives remaining: %s\n", $lives); 
    }

    if(implode($guessString) == implode($guessWord))
    {
        printf("\nYou have guessed the word!!\n"); 
        printf("You have achieved %s points",$points,"\n"); 
        break; 
    }

    echo implode($guessString) , "\n";    
}

if($lives == 0)
{
    echo "You have lost!\n"; 
    printf("The word was: %s", implode($guessWord),"\n"); 
    echo "\n"; 
}

// if the user didnt lose, we record their name and score and output it to a score file. 
if($lives > 0)
{
    $fileName ="hangmanScores.txt"; 
    $contents = file($fileName); 
    $player = readline("\n\nEnter player name for high score table: "); 
    $fileOut = fopen("hangmanScores.txt", "ab"); 

    // Loop through the contents in the file.
    foreach($contents as $line)
    {
        // If the string in the file contains the player who has just played
        if(str_contains($line, $player))
        {
            // We parse the current points in the file to int enabling addition of points accumulated in the current game. 
            $temp_points = (int)substr($line, -3);
            $points = $points + $temp_points;  

            // We replace the current line in file with the old points. 
            $replace = str_replace($line, " ", $contents); 
            file_put_contents($fileName, $replace); 
        }
    }

    
    $scoreText = "${player}\t${points}\n";
    fwrite($fileOut, $scoreText); 
    // echo $scoreText; 
    fclose($fileOut); 
}

/*
     
    ✔ CHALLENGE1: Keep track of the letters still available for selection and display this to the user during each turn 
    ✔ CHALLENGE2: If the user already has a score in the file, the new score should be added to their existing score. 
    ✔ CHALLENGE3: When the user runs out of lives, the game should tell them the word they failed to guess. 
*/ 