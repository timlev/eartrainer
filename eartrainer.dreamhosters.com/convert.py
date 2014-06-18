import os

all_files = [x for x in os.listdir("sounds")]

for file in all_files:
	if file.endswith(".mp3") and file.replace(".mp3",".wav") not in all_files:
		os.system("/cygdrive/c/Program\ Files\ \(x86\)/Lame\ For\ Audacity/lame.exe --decode sounds/" + file + " sounds/" + file.replace(".mp3",".wav"))
