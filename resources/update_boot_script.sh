#!/bin/bash
# Update boot script to include Jeedom system auto update

set -e

FORCE_UPDATE=0
while getopts ":f" opt; do
  case $opt in
    f)
      FORCE_UPDATE=1
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      ;;
  esac
done

# Check if running on ARM64 architecture
if [ $(uname -m) != 'aarch64' ] ; then
  echo "This is designed for ARM64 architecture only"
  exit 1
fi

# Need to be root
if [ $(id -u) != 0 ] ; then
  echo "Superuser rights (root) are required to update boot script"
  exit 2
fi

if [ ! -e "/boot/boot.cmd" ]; then
  if [ ! -e "/boot/boot.scr" ]; then
    echo "Unable to find boot script, does system relies on extlinux.conf file to boot?"
    exit 3
  fi
  strings /boot/boot.scr | tail -n +2 > ./boot.txt
  if grep -q '# flash-kernel:' ./boot.txt; then
    fk_file=$(grep '# flash-kernel:' ./boot.txt | awk -F': ' '{print $2}')
    echo "Detected flash-kernel utility script source: /etc/flash-kernel/bootscript/$fk_file"
    cp /etc/flash-kernel/bootscript/$fk_file ./boot.txt
  fi
else
  cp /boot/boot.cmd ./boot.txt
fi

if grep -q '# Jeedom boot script including automatic system update' ./boot.txt && [ $FORCE_UPDATE -eq 0 ]; then
  rm ./boot.txt
  echo "Boot script already updated, no changes made"
  exit 4
fi

if [ $FORCE_UPDATE -eq 1 ]; then
  sed -i '/# Jeedom boot script including automatic system update/,/# End of Jeedom system update script/d' ./boot.txt
fi

# Add Jeedom system update to boot script
echo "Updating boot script to include Jeedom system auto update"
cat << EOF > ./boot.cmd

# Jeedom boot script including automatic system update
env set update_filename "JeedomSystemUpdate.img.gz"
env set update_load_addr "0x1000000"
env set update_src ""

if test -e \${devtype} \${devnum}:2 /var/www/html/install/update/\${update_filename}; then
  env set update_found "Found Jeedom system update on disk \${devtype}\${devnum}"
  env set update_src "\${devtype} \${devnum}"
  env set update_part "2"
  env set update_path "/var/www/html/install/update/"
else
  usb start
  if load usb 0:1 \${update_load_addr} /JeedomSystemUpdate.ini; then
    env import -t \${update_load_addr} \${filesize}
  fi

  if test -e usb 0:1 /\${update_filename}; then
    env set update_found "Found Jeedom system update on USB drive"
    env set update_src "usb 0"
    env set update_part "1"
    env set update_path "/"
  fi
fi

if test -n \${update_src}; then
  if test -e \${update_src}:1 /JeedomSystemUpdate.log; then
  # Found system update log file, not redoing update
  else
    echo \${update_found}
    env set update_load "Loading update file, please wait..."
    echo \${update_load}

    # load compressed file to ram address 0x30000000 to prevent memory conflicts
    if load \${update_src}:\${update_part} 0x30000000 \${update_path}\${update_filename}; then
      env set update_mmc "Writing \${devtype}\${devnum}..."
      echo \${update_mmc}

      if gzwrite \${devtype} \${devnum} 0x30000000 \${filesize} 400000 0; then
        env set update_state "SUCCESS: Jeedom system update is done, will reboot once ready"
      else
        env set update_state "ERROR: Cannot write system update, check for JeedomSystemUpdate.img.gz file corruption"
      fi
    else
      env set update_mmc "Unable to write to \${devtype}\${devnum}"
      env set update_state "ERROR: Cannot load system update file"
    fi
    echo \${update_state}

    env set update_z "--------------------"
    env set update_zredo "Remove this log file if you want to redo the system update\0"

    sleep 5
    if test \${update_state} = "SUCCESS: Jeedom system update is done, will reboot once ready"; then
      if test \${update_src} = "usb 0"; then
		    env export -t \${update_load_addr} update_found update_load update_mmc update_state update_z update_zredo
		    save \${update_src}:1 \${update_load_addr} /JeedomSystemUpdate.log \${filesize}
      fi

      mmc rescan
      env set distro_bootpart "2"
      load \${devtype} \${devnum}:2 \${update_load_addr} /boot/boot.scr
      source \${update_load_addr}
    else
      env export -t \${update_load_addr} update_found update_load update_mmc update_state update_z update_zredo
      save \${update_src}:1 \${update_load_addr} /JeedomSystemUpdate.log \${filesize}
    fi
  fi
fi
# End of Jeedom system update script

EOF
cat ./boot.txt >> ./boot.cmd
rm ./boot.txt

# Create backup of existing boot script
if [ ! -e "/boot/boot.scr.bak" ]; then
  cp /boot/boot.scr /boot/boot.scr.bak
fi

# Update U-Boot boot script
if [ -n "$fk_file" ]; then
  if [ ! -e "/etc/flash-kernel/bootscript/$fk_file.bak" ]; then
    cp /etc/flash-kernel/bootscript/$fk_file /etc/flash-kernel/bootscript/$fk_file.bak
  fi
  mv ./boot.cmd /etc/flash-kernel/bootscript/$fk_file
  flash-kernel
else
  mv ./boot.cmd /boot/boot.cmd
  mkimage -A arm64 -T script -C none -d /boot/boot.cmd /boot/boot.scr
fi
echo "Boot script updated successfully"
exit 0
