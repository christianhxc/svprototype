@ECHO OFF
start C:\xampp\php\php.exe C:\xampp\htdocs\sisvig2\mat\demonio\demonio.php
echo Ejecutando script de php...
ping -n 60 127.0.0.1 > log
exit