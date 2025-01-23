#!/bin/bash
set -e

cat .devcontainer/.bashrc >> ~/.bashrc
git config --global --add safe.directory '*'