#!/bin/bash

# Make sure only root can run our script
if [ "$(id -u)" != "0" ]; then
   echo "This script must be run as root" 1>&2
   exit 1
fi

path_to_root="/usr/share/nginx/html/pifm"

chmod 777 $path_to_root/*
chmod 777 $path_to_root/*/*

frequency=$(cat $path_to_root/points/frequency)
play_me=$(cat $path_to_root/points/play_me)

while true; do
   echo "$play_me" > $path_to_root/points/current
   sudo sox -t mp3 "$path_to_root/playlist/$play_me" -t wav - | sudo $path_to_root/bin/pi_fm_rds -freq $frequency -audio - &
   pid=$!
 
   trap "kill $pid 2> /dev/null" EXIT

   while kill -0 $pid 2> /dev/null; do
       frequency2=$(cat $path_to_root/points/frequency)
       play_me2=$(cat $path_to_root/points/play_me)
       if [ "$frequency" == "$frequency2" ] && [ "$play_me" == "$play_me2" ]; then
            sleep 0.2              
       else
            echo -e "\033[0;32mSomething changed...restarting\033[0m\n"
            killall sox
            killall pi_fm_rds
            killall sox
            killall pi_fm_rds
            exec $path_to_root/bin/daemon_pifm.sh            
       fi
   done
   trap - EXIT

   for song in  $path_to_root/playlist/*.mp3; do
      echo "$frequency"
      echo "$song" > $path_to_root/points/current
      sudo sox -t mp3 "$path_to_root/playlist/$play_me" -t wav - | sudo $path_to_root/bin/pi_fm_rds -freq $frequency -audio - &
      pid=$!

      trap "kill $pid 2> /dev/null" EXIT

      while kill -0 $pid 2> /dev/null; do
          frequency2=$(cat $path_to_root/points/frequency)
          play_me2=$(cat $path_to_root/points/play_me)
          if [ "$frequency" == "$frequency2" ] && [ "$play_me" == "$play_me2" ]; then
               sleep 0.2              
          else
               echo -e "\033[0;32mSomething changed...restarting\033[0m\n"
               killall sox
          	   killall pi_fm_rds
           	   killall sox
               killall pi_fm_rds
               exec $path_to_root/bin/daemon_pifm.sh
          fi
      done

      trap - EXIT
   done
done

killall sox
exec $path_to_root/bin/daemon_pifm.sh
