#!/bin/bash
alsactl restore
touch /tmp/alarmMicLevel
chmod 644 /tmp/alarmMicLevel
while (true) do
  rec -c 2 /tmp/temp.wav trim 0 00:05 2>/dev/null;
  sox -t .wav /tmp/temp.wav -n stat 2>&1 | grep -e 'Maximum delta' | tail --silent -c9 > /tmp/alarmMicLevel;
done
