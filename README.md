# compose-pkg-duckietown-blockly

Duckietown Blockly package for the \compose\ platform.

## Introduction to Blockly

Blockly is a web-based editor that lets you define algorithms using interlocking blocks.


## How to create new blocks

Custom blocks for Blockly are defined in the directory `/blockly_data/`.
There are two directories that are important in there, namely `blocks` and `generators`.

The directory `blocks/` contains the Blockly description of our blocks.
Blockly description files are written in javascript and describe the structure
of a block along with its inputs and outputs.

The directory `generators/` contains the information about how to convert an instance
of a block into programming language (e.g., Python) that the back-end can execute.


Go to `/blockly_data/blocks/`. Each JS file in this directory contains
a set of blocks. You can either create a new JS file or add blocks to an existing one.

TODO
