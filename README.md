# Static-Page-Builder

### 一个基于Blade视图引擎和Markdown实现的静态页面生成器

#### 目录结构

* cache //视图渲染缓存目录
* config  //配置文件目录
* content  //Markdown文章目录
* output //输出目录
* source //静态文件目录
* src  //核心逻辑目录
* template //模板目录
* vendor //Composer组件目录
* .env //环境变量
* cli.php //命令行入口文件
* ...

### 环境要求

`PHP7.4+`

### 快速使用

**直接渲染导出**

```php
php cli.php
```

**监听目录自动热渲染**

```php
php cli.php watch
```

### 部署

配置nginx，将根目录设置为output目录即可

```nginx
server {
        listen 80;
        listen [::]:80;

        root /项目绝对地址/output;
        client_max_body_size 1024m;
        index index.html index.htm index.nginx-debian.html;

        location / {
                try_files $uri $uri/ =404;
        }
}
```

或者使用php内置的web服务器

```php
/**
 * 访问 http://127.0.0.1:8080 查看效果
 */
php cli.php serve
```

#### 模板编写

在编写模板之前，我们需要先了解一下渲染流程

1. 首先从 template/ 的入口文件开始渲染
2. 随后渲染 template/page/ 目录下的所有页面视图文件
3. 模板中Markdown跳转链接会直接返回，渲染任务会投递至队列
4. 最后根据队列执行未完成的渲染任务

##### ContentCollector

##### ContentDrawer

##### ContentLinker

##### DirDrawer

##### ContentDirLinker

