# 72pcms
72pcms企业建站cms


# 说明

1、基于ThinkPHP3.2

2、PHP+Mysql

3、后台登录：/Fladmin/Login，账号：admin888，密码：admin

4、恢复后台默认账号密码：/Fladmin/Login/recoverpwd


# 安装

1、 导入数据库
1) 打开根目录下的fl72p.sql文件，将 http://www.72p.org 改成自己的站点根网址，格式：http://+域名
2) 导入数据库

2、 修改数据库连接参数

打开/Flhome/Common/Conf/config.php文件,修改相关配置


3、 登录后台->顶部按钮，更新缓存：Fladmin/Index/upcache


# 注意
只能放在根目录