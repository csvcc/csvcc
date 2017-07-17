<?php
/**
 * @author  HID丨emotion
 * @license http://www.hids.vip
 * @version 2017-2-16 0016 10:13:01
 */

namespace hidsvip\http\Uploader;

use hidsvip\http\Uploader;

class Local extends Uploader
{

    protected $option = [
        'path'     => 'uploads/',
        'hostname' => '/public/uploads/',
    ];

    protected function formatOption($option)
    {
        if (isset($option['path']) && substr($option['path'], -1) != DIRECTORY_SEPARATOR) {
            $option['path'] .= DIRECTORY_SEPARATOR;
        }
        if (isset($option['hostname']) && substr($option['hostname'], -1) != '/') {
            $option['hostname'] .= '/';
        }

        return array_merge($this->option, $option);
    }

    public function upload()
    {
        $res = [];
        if (!empty($this->files)) {
            foreach ($this->files as $k => $v) {
                $res[ $k ] = $this->saveFile($v);
            }
        }

        return $res;
    }

    private function saveFile($files)
    {
        $res = '';
        if (is_array($files['tmp_name'])) {
            foreach ($files['tmp_name'] as $k => $v) {
                $res[ $k ] = $this->saveFile([
                                                 'name'     => $files['name'][ $k ],
                                                 'type'     => $files['type'][ $k ],
                                                 'tmp_name' => $files['tmp_name'][ $k ],
                                                 'error'    => $files['error'][ $k ],
                                                 'size'     => $files['size'][ $k ],
                                             ]);
            }
        } elseif (!empty($files['tmp_name'])) {
            $path = $this->buildFilePath($files);
            if (move_uploaded_file($files['tmp_name'], $path)) {
                $res = $this->buildUrlPath($path);
            } else {
                $res = $files['error'];
            }
        }

        return $res;
    }

    private function buildFilePath($file)
    {
        $path     = $this->option['path'] . date('Ymd') . DIRECTORY_SEPARATOR;
        $filename = md5_file($file['tmp_name']) . strrchr($file['name'], '.');

        if (!is_dir($path) && !mkdir($path, 0755, true)) {
            throw new \Exception('directory ' . $path . ' cannot be created');
        }

        return $path . $filename;
    }

    private function buildUrlPath($filePath)
    {
        return str_replace('\\', '/', str_replace($this->option['path'], $this->option['hostname'], $filePath));
    }
}