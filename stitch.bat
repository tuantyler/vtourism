@echo off
setlocal enabledelayedexpansion
cd %1

set "image_list="
for %%F in (*.jpg) do set "image_list=!image_list!%%~dpnxF "

"C:/Program Files/Hugin/bin/pto_gen.exe" -o project_from_gen.pto %image_list%
"C:/Program Files/Hugin/bin/cpfind.exe" --multirow --celeste -o project_from_gen.pto project_from_gen.pto
"C:/Program Files/Hugin/bin/cpclean.exe" -o project_from_gen_and_cpfind.pto project_from_gen.pto
"C:/Program Files/Hugin/bin/linefind.exe" -o project_from_gen_and_cpfind.pto project_from_gen_and_cpfind.pto 
"C:/Program Files/Hugin/bin/autooptimiser.exe" -a -l -s -m -o project_from_gen_and_cpfind_and_clean2_opt.pto project_from_gen_and_cpfind.pto
"C:/Program Files/Hugin/bin/pano_modify.exe" --center --straighten --canvas=AUTO --crop=AUTO -o project_from_gen_and_cpfind_and_clean2_opt_modify.pto project_from_gen_and_cpfind_and_clean2_opt.pto
"C:/Program Files/Hugin/bin/hugin_executor.exe" --stitching --prefix=pano project_from_gen_and_cpfind_and_clean2_opt_modify.pto