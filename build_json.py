import os, json
import download_wiktionary_word


master_wordlist = []
lessons_dict = {}
lessonsdir = "Lessons"
sounddir = "sounds"
lessons = [os.path.join(lessonsdir, f) for f in os.listdir(lessonsdir) if '_pairs.txt' in f]

# print lessons

def import_lesson(f):
    lesson_name = f.replace("_pairs.txt","")
    # print lesson_name
    lesson_pairs = []
    with open(f,"r") as fp:
        for line in fp.readlines():
            lesson_pairs.append(line.rstrip("\n").split(", "))
    lesson = {lesson_name : lesson_pairs}
    total = len(lesson[lesson_name])
    lessons_dict[lesson_name] = lesson_pairs
    return lesson

def build_word_list(l):
    for k in l.keys():
        for p in l[k]:
            for w in p:
                master_wordlist.append(w)
    return master_wordlist

def write_json():
    with open("lessons.json","w") as fp:
        fp.write("var lessons = ")
        json.dump(lessons_dict,fp)

def download_sound_files(master_word_list):
    print "Downloading sound files ..."
    soundfiles = [f.replace(".mp3","") for f in os.listdir(sounddir) if f.endswith(".mp3")]

    for word in [word for word in master_word_list if word not in soundfiles]:
        downloaded_word = False
        try:
            oggpath = download_wiktionary_word.get_wiki(word, sounddir)
            if oggpath != 2:
                download_wiktionary_word.convert_ogg_to_mp3(oggpath, True)
                downloaded_word = True
        except:
            print "Could't convert from wiki", word
        if downloaded_word == False:
            try:
                mp3path = download_wiktionary_word.download_gstatic(word, sounddir)
            except:
                print "Couldn't download from GStatic"

if __name__ == '__main__':
    for lesson in lessons:
        build_word_list(import_lesson(lesson))

    master_wordlist = list(set(master_wordlist))
    # print master_wordlist
    download_sound_files(master_wordlist)
    soundfiles = [f.replace(".mp3","") for f in os.listdir("./sounds/") if f.endswith(".mp3")]
    missing_words = "\n".join([word for word in master_wordlist if word not in soundfiles])

    with open("missing_words.txt","wb") as wb:
        wb.write(missing_words)
    # print json.dumps(lessons_dict)
    write_json()
