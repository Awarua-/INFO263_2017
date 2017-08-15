@ECHO OFF

start /min ssh -L 13389:law110-74.canterbury.ac.nz:3389 linux.cosc.canterbury.ac.nz -l dmw99 -N

ECHO "Connect to remote session"

MSTSC /v localhost:13389 /w:1920 /h:1080 /f
