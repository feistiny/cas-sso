#!/bin/bash

curl "http://101.43.82.144:9500/server/cas_auth?username=admin&password=123456"

http://101.43.82.144:9500/server/cas_auth?redirect_type=post&username=lzf&password=123456&redirect_url=http://9501.lzf.itbtx.cn/client1/no_auth_page
http://101.43.82.144:9500/server/cas_auth?redirect_type=post&username=lzf&password=123456&redirect_url=http://9501.lzf.itbtx.cn/
http://101.43.82.144:9500/server/cas_auth?service_id=1&redirect_type=post&username=lzf&password=123456&redirect_url=http://9501.lzf.itbtx.cn/client1/auth_page
http://101.43.82.144:9502/client1/auth_page?service_id=2&redirect_type=get&username=lzf&password=123456&redirect_url=http://101.43.82.144:9502/client1/auth_page
http://9501.lzf.itbtx.cn/client1/cas_back?st=1&redirect_url=http://9501.lzf.itbtx.cn/

http://9501.lzf.itbtx.cn/client1/auth_page?service_id=1&redirect_type=post&username=lzf&password=123456&redirect_url=http://9501.lzf.itbtx.cn/client1/auth_page
http://9502.lzf.itbtx.cn/client2/auth_page?service_id=2&redirect_url=http://9502.lzf.itbtx.cn/client2/auth_page

# 带登录界面的
https://9501.lzf.itbtx.cn/server/cas_login_form?service_id=1&redirect_url=http://9501.lzf.itbtx.cn