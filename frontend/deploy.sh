#!/usr/bin/env sh

# Hentikan proses jika terjadi error
set -e

# Build proyek (membuat folder 'dist')
npm run build

# Masuk ke folder hasil build
cd frontend/dist

# Inisialisasi git baru di dalam folder dist dan commit
git init
git add -A
git commit -m 'deploy'

# Push secara paksa ke branch gh-pages di repository Anda
git push -f git@github.com:SeorangManusia99/dana-bulanan-app.git main:gh-pages

cd -