# imgcompos combines 3 images into one, preserving differences

To combine images, make three still photos of the object in different positions. 
Then save it as 1.png, 2.png, 3.png in 'images2' folder.
All files must be of the same size. 
Put the folder on php-capable web server along with combine.php file. 
Open combine.php in browser.

If no webserver is available to you, you can copy combine3.php and images2 folder into one directory, navigate to it and and run

php combine3.php > res.jpg

Only installed php with gd is requred. 
