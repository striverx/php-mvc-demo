<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<style>
	table{background-color:#d9d9d9;}
	tr{height:24px;}
	td,th{background:#fff;padding:10px;}
</style>
</head>
<body>
	<table border=0 cellspacing=1 cellpadding=0 >
		<tr>
			<th>文件：</th>
			<td><?=$e['file']?></td>
		</tr>
		<tr>
			<th>行：</th>
			<td><?=$e['line']?></td>
		</tr>
		<tr>
			<th>信息：</th>
			<td><?=$e['msg']?></td>
		</tr>
	</table>
</body>
</html>