#!/bin/bash
# Skrip untuk menyalin isi folder storage/app/public/categories ke public/storage/categories

SRC_DIR="storage/app/public/categories/"
DEST_DIR="public/storage/categories/"

echo "Menyalin file dari $SRC_DIR ke $DEST_DIR ..."

if [ ! -d "$SRC_DIR" ]; then
  echo "Folder sumber $SRC_DIR tidak ditemukan!"
  exit 1
fi

mkdir -p "$DEST_DIR"

cp -r "$SRC_DIR"* "$DEST_DIR"

echo "Penyalinan selesai."
