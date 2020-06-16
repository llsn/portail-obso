@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../clue/graph-composer/bin/graph-composer
php "%BIN_TARGET%" %*
