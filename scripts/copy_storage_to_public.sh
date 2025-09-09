#!/bin/bash
# Skrip untuk menyalin isi folder storage/app/public ke public/storage

SRC_DIR="storage/app/public/"
DEST_DIR="public/storage/"

echo "Menyalin file dari $SRC_DIR ke $DEST_DIR ..."

if [ ! -d "$SRC_DIR" ]; then
  echo "Folder sumber $SRC_DIR tidak ditemukan!"
  exit 1
fi

mkdir -p "$DEST_DIR"

cp -r "$SRC_DIR"* "$DEST_DIR"

echo "Penyalinan selesai."
