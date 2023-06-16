import cv2
import numpy as np
from flask import Flask, request, jsonify
import os
import subprocess
import requests

app = Flask(__name__)
requests.packages.urllib3.disable_warnings() 

def updatedatabase(video_id):
    url = f"https://localhost/update-database/{video_id}"
    response = requests.get(url, verify=False)

    if response.status_code == 200:
        print("URL updated in the database.")
    elif response.status_code == 404:
        print("pano does not exist in the processed folder.")
    else:
        print("Failed to update the database.")

def process_video_frames(videofile):
    print("Video file:" , videofile)
    frames_dir = os.path.join('processed', os.path.splitext(os.path.basename(videofile))[0])
    os.makedirs(frames_dir, exist_ok=True)

    vid_cap = cv2.VideoCapture(videofile)
    sift = cv2.xfeatures2d.SIFT_create()
    success, last = vid_cap.read()
    cv2.imwrite(os.path.join(frames_dir, 'frame0.jpg'), last)
    print("Captured frame0.jpg")
    count = 1
    frame_num = 1

    w = int(last.shape[1] * 2 / 3)
    stride = 40   
    min_match_num = 100  
    max_match_num = 600 

    while success:
        if count % stride == 0:
            kp1, des1 = sift.detectAndCompute(last[:, -w:], None)
            kp2, des2 = sift.detectAndCompute(image[:, :w], None)

            bf = cv2.BFMatcher(normType=cv2.NORM_L2)  
            matches = bf.knnMatch(des1, des2, k=2)

            match_ratio = 0.6

            valid_matches = []
            for m1, m2 in matches:
                if m1.distance < match_ratio * m2.distance:
                    valid_matches.append(m1)

            if len(valid_matches) > 4:
                img1_pts = []
                img2_pts = []
                for match in valid_matches:
                    img1_pts.append(kp1[match.queryIdx].pt)
                    img2_pts.append(kp2[match.trainIdx].pt)

                img1_pts = np.float32(img1_pts).reshape(-1, 1, 2)
                img2_pts = np.float32(img2_pts).reshape(-1, 1, 2)

                _, mask = cv2.findHomography(img1_pts, img2_pts,
                                             cv2.RANSAC, 5.0)

                if min_match_num < np.count_nonzero(mask) < max_match_num:
                    last = image
                    frame_path = os.path.join(frames_dir, 'frame{}.jpg'.format(frame_num))
                    print("Captured", frame_path)
                    cv2.imwrite(frame_path, last)
                    frame_num += 1
        success, image = vid_cap.read()
        count += 1



@app.route('/process-video', methods=['POST'])
def process_video():
    data = request.get_json()
    print(data)
    id = data.get('id')
    videofile = data.get('videofile')
    folder_name = os.path.splitext(videofile)[0]
    folder_path = os.path.join('processed', folder_name)
    os.makedirs(folder_path, exist_ok=True)
    videofile_path = os.path.join('videos', videofile)
    process_video_frames(videofile_path)
    
    stitch_command = ['stitch.bat', folder_path]

    subprocess.run(stitch_command, shell=True, check=True)

    makepano_command = [
        r'C:\laragon\www\js\krpano\krpanotools.exe',
        'makepano',
        f'{folder_path}/pano.tif'
    ]
    subprocess.run(makepano_command, shell=True, check=True)

    updatedatabase(id)
    return jsonify({'message': 'Video processed successfully.'})


if __name__ == "__main__":
    app.run()
