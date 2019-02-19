# Rydoo-Khameleon

This data file conversion script converts Rydoo Expense Excel export files (XLSX) into Khameleon-importable data files (CSV).

The repository for this script can be found on GitHub [here](https://github.com/MasonWray/rydoo-khameleon).

The XLSX reader repository can be found on GitHub [here](https://github.com/gneustaetter/XLSXReader).

## Usage

This command line utility can be executed from a command line interface by invoking the PHP interpreter on the **converter.php** script as follows (assuming the script is in the current working directory):

`php converter.php`

The script will convert all valid XLSX data files in it's *root directory*, and place the output files alongside the input files. Since CMD does not support UNC file paths as workinng directories, a full file path may be invoked as follows:

`php "\\path\to\network\folder\converter.php"`

While the quotes are not strictly necessary, they are required when specifying any filepath that contains whitespace characters.

Some sample files are included in the `test\` directory. `rtest.xlsx` is the Rydoo export that was used as a reference, and `ktest.csv` is was used as a model for Khameleon data.

**NOTE:** PHP must be properly installed, and is not included with Windows by default. You can download the PHP interpreter [here](https://windows.php.net/download).

## Test Points
- [ ] Validate sign of expense amounts (+/-)
- [ ] Verify Excel -> ISO-8601 date conversion is performed correctly (Lotus 123 bug)
- [ ] Determine input value source for long and short description fields

## Known Bugs
- Failure to find XLSX files when one or more input file is open in Excel. Caused by inability to obtain lock on Excel temp file.
- Date conversion calculation is advanced by two days. Hardcoded offset handles this issue currently, may be final solution.

## Changelog
| **Version** | **Date**     | **Change**                                                |
| ----------- | ------------ | --------------------------------------------------------- |
| *v0.1a*     | 19 Feb. 2019 | Prototype script created, ready for initial user testing. |