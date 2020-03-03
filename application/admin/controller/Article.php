<?php

namespace app\admin\controller;

use app\admin\controller\Base;
use think\Db;

class Article extends Base
{
    public function index()
    {
        $info = DB::name('article')
            ->join('pre_category', 'pre_category.`cid`=pre_article.`cid`')
            ->where(1)
            ->order('aid', 'ASC')
            ->select();
        // dump($info);

        // 搜索
        if (!empty($_GET)) {
            // dump($_GET);
            // die;
            $type = $_GET['type'];
            $key = $_GET['key'];
            if ($type != '') {
                $info = Db::name('article')->where('title', 'like', "%$key%")->where('pre_article.cid', $type)->join('pre_category', 'pre_category.`cid`=pre_article.`cid`')->select();
                
                $this->assign('type',$type);
            } else {
                $info = Db::name('article')->where('title', 'like', "%$key%")->
                    // where('pre_article.cid',$type)->
                    join('pre_category', 'pre_category.`cid`=pre_article.`cid`')->select();
            }
        }


        $type = isset($_GET['type'])?$_GET['type']:0;
        $this->assign('type',$type);

        return $this->fetch('article-list', [
            'info' => $info,
            // 'cate' => $cate_array
        ]);
    }
    // 渲染模板
    // 添加
    public function add()
    {
        return $this->fetch('article-add');
    }

    // 修改
    public function edit($id)
    {
        // find()只返回一条数据
        // echo $id;
        $info = DB::name('article')->where('aid', $id)->find();
        // dump($info);
        return $this->fetch('article-edit', [
            'info' => $info
        ]);
    }
    // 更新
    public function update()
    {
        // dump($_POST);
        // dump($_FILES);
        $id = $_POST['id'];
        // die;
        $file = request()->file('img');

        // 移动到框架应用根目录/public/uploads/ 目录下
        if ($file) {
            // 如果有图片就更新
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                // 有旧图片删除
                $old_img = Db::name('article')->where('aid', $id)->find();
                $path = 'uploads/' . $old_img['a_img'];
                unlink($path);
                $img =  $info->getSaveName();
                Db::name('article')->where('aid', $id)->update(
                    [
                        'a_img' => $img
                    ]
                );
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename(); 
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        $title = $_POST['articletitle'];
        $type = $_POST['articletype'];
        $author = trim($_POST['author']);
        $content = $_POST['editorValue'];
        $time = time();

        // echo $content;
        // die;
        // 更新数据 失败返回0

        $res = Db::name('article')->where('aid', $id)->update(
            [

                'title' => $title,
                'content' => $content,
                'author' => $author,
                'pubtime' => $time,
                'cid' => $type
            ]
        );
        // echo $res;
        if ($res != 0) {
            // layui-layer-close1

            echo "<script>
            alert('修改成功,刷新后显示修改后内容');
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);//关闭当前页
            window.parent.location.replace(location.href)//刷新父级页面
            
            </script>";
        }
    }


    // 处理新增数据
    public function upload()
    {
        $file = request()->file('img');
        // dump($file);
        // dump($_FILES);
        // dump($_POST);
        // die;
        if ($file) {
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            
            if ($info) {
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // $img  =  $info->getFilename(); 
                $img  =  $info->getSaveName();


                $image = \think\Image::open(request()->file('img'));
                $image->thumb(150,150,\think\Image::THUMB_SCALING)->save(ROOT_PATH . 'public' . DS . 'uploads'.DS.'thumb'.DS.$info->getFilename());


                $title = $_POST['articletitle'];
                $type = $_POST['articletype'];
                $author = trim($_POST['author']);
                $content = $_POST['editorValue'];
                $time = time();
                $res = DB::name('article')->insert([
                    'title' => $title,
                    'content' => $content,
                    'author' => $author,
                    'pubtime' => $time,
                    'cid' => $type,
                    'a_img' => $img
                ]);
                if ($res == 1) {
                    echo '<script>
            alert("添加成功,刷新后显示修改后内容");
            window.parent.close()
            </script>';
                } else {
                    echo '错误';
                    die;
                }
            } else {
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
        die;
    }

    // public function thumb($file,$path='uploads/thumb'){
    //     if(!file_exists($path)){
    //         mkdir($path,0777);
    //     }
    // }

    // 删除
    public function remove($id)
    {
        //    echo $id;
        $old_img = Db::name('article')->where('aid', $id)->find();
        $path = 'uploads/' . $old_img['a_img'];
        unlink($path);
        $res = Db::name('article')->delete($id);
        if ($res != 0) {
            // 删除成功
            echo 1;
        } else {
            echo 0;
        }
        // die;
    }
}
