- Python 3.6.3
	- numpy 1.14.3
	- OpenCV 3.4.0.12
	- OpenCV-contrib 3.4.0.12

Note that **OpenCV and OpenCV-contrib must be lower than (not including) 3.4.3**. Because SIFT is a patented algorithm and has been excluded after 3.4.3 (see [Issue: Include non-free algorithms](https://github.com/skvark/opencv-python/issues/126)). Installing an old version (up to 3.4.2) can solve this problem. For example, with pip:

pip install opencv-python==3.4.2.17
pip install opencv-contrib-python==3.4.2.17

