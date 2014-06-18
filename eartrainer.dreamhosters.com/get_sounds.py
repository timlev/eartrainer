import os
import download_dict_sound
sounddir = os.path.relpath("sounds")
print sounddir

sound_files = [x for x in os.listdir(sounddir)]

def find_all_words():
	all_words = []
	text_files = [x for x in os.listdir(os.getcwd()) if "_pairs.txt" in x]
	#get words from each _pairs.txt file and put in all_words list
	for f in text_files:
		with open(f, "r") as fp:
			whole_file = fp.readlines()
		for num, line in enumerate(whole_file):
			if "," in line:
				line = line.rstrip()
				#if "cap," in line:
				#	print f, num
				word1, word2 = line.split(", ")
				all_words.append(word1)
				all_words.append(word2)
	#print all_words, len(all_words), len(list(set(all_words)))
	return list(set(all_words))


#print find_all_words()

#print sound_files

sound_words = list(set([os.path.splitext(x)[0] for x in sound_files]))
#print sound_words


missing_sound_files = [x for x in find_all_words() if x not in sound_words]
print "Missing sound files:", missing_sound_files

#get sound files
if len(missing_sound_files) > 0:
	need_to_record = []
	print "Downloading sound files ..."
	for word in missing_sound_files:
		try:
			download_dict_sound.download(word, directory=sounddir)
			download_dict_sound.convert_mp3_to_wav(os.path.join(sounddir,word + ".mp3"))
		except:
			"Download didn't work."
			need_to_record.append(word)
	print "Need to record:", need_to_record


mp3files = [os.path.splitext(x)[0] for x in sound_files if os.path.splitext(x)[1] == ".mp3"]
wavefiles = [os.path.splitext(x)[0] for x in sound_files if os.path.splitext(x)[1] == ".wav"]
print "MP3 Files:", len(mp3files)
print "WAVE Files:", len(wavefiles)
missing_wave_files = [x for x in mp3files if x not in wavefiles]
missing_mp3_files = [x for x in wavefiles if x not in mp3files]
print "Need to get wave file:", missing_wave_files
print "Need to get mp3 file:", missing_mp3_files

for word in missing_wave_files:
	download_dict_sound.convert_mp3_to_wav(os.path.join(sounddir,word + ".mp3"))
