## 使用docker构建和使用服务

```
docker build -t lichunyin/yimei .
```	
```	
docker run -d -p443:443 -p80:80 -p3306:3306 -p33060:33060 lichunyin/yimei
```	

接口请求地址 https://127.0.0.1/api

访问接口文档 https://127.0.0.1/api-doc


配置关键问题:
1. 抖音的授权url配置必须使用https，并且域名配置不能带有端口号。