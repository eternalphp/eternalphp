<!DOCTYPE html>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/style.css" />
<script type="text/javascript" src="__PUBLIC__/js/jquery.js"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/jquery.artDialog.source.js?skin=default"></script>
<script type="text/javascript" src="__PUBLIC__/plugins/artDialog/plugins/iframeTools.source.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/common.js"></script>

<style type="text/css">
        body {
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }

        @media (max-width: 980px) {
            /* Enable use of floated navbar text */
            .navbar-text.pull-right {
                float: none;
                padding-left: 5px;
                padding-right: 5px;
            }
        }


        .list-per{background: #fff;border-radius: 5px;position: relative;margin-top: 10px;}
        .list-per span{font-size: 13px;color: #333;text-align: center;position: absolute;left: 12%;right: 0;top: 64.5%;}
        .list-per h4{font-size: 16px;padding: 0px 15px 10px 15px;position: absolute;left: 0;top: 10px;}
        .list-per span.sex{top: 55.5%;}
		.list-per span.age{top: 60.5%;}
        .chart-list{margin-bottom: 10px;}

    </style>
</head>
<body>

 <form class="form-inline definewidth m20" action="" method="post">
 </form>


 <table width="100%" class="table table-bordered definewidth m10">
  <thead>
    <tr>
      <td width="3%" height="631" align="center">
	  
			<div class="chart-list">
			
				<div class="container-fluid">
			
					<div class="list-per">
						<h4 class="text-left">支部总人数:<?=$userCount?>人</h4>
						<div id="main0" style="width:100%;height:240px;margin:0 auto;"></div>
						<span>政治面貌</span>
					</div>
					
					<div class="list-per">
						<h4 class="text-left" style="top: -20px;">在职党员性别统计</h4>
						<div id="main1" style="width:100%;height:230px;margin:0 auto;"></div>
						<span class="sex">性别</span>
					</div>
			
					<div class="list-per" style="padding-bottom: 15px;">
						<h4 class="text-left" style="top: -20px;">在职党员年龄统计</h4>
						<div id="main2" style="width:100%;height:240px;margin:0 auto;"></div>
						<span class="age">年龄</span>
					</div>
			
					<div class="list-per">
						<h4 class="text-left" style="top: -20px;">在职党员文化程度统计</h4>
						<div id="main3" style="width:100%;height:240px;margin:0 auto;"></div>
						<span>文化程度</span>
					</div>
			
			
				</div>
			</div>	  
	  </td>
    </tr>
  </thead>
</table>


<script src="__PUBLIC__/js/echarts.min.js"></script>
<script>
    var myChart = echarts.init(document.getElementById('main0'));
    option = {
        tooltip: {
            trigger: 'item',
            show:false
//            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'left',
            left:'10',
            top:'50',
            data:['党员','预备党员','入党积极分子','团员','群众'],
            itemGap:10,
            itemHeight: 15,
            itemWidth: 15

        },

        color:['#93e1f2','#ffe183','#fdaeca','#91cea5','#f96b68'],
        series: [
            {
                name:'访问来源',
                type:'pie',
                radius: ['25%', '50%'],
                center:['56%','68%'],
                avoidLabelOverlap: false,



                label: {
                    normal: {
                        formatter: '{d}%',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        shadowBlur:3,
                        shadowOffsetX: 2,
                        shadowOffsetY: 2,
                        shadowColor: '#999',
                        padding: [0, 7],
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            abg: {
                                 backgroundColor: '#333',
                                 width: '100%',
                                 align: 'right',
                                 height: 22,
                                 borderRadius: [4, 4, 0, 0]
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }

                },

                labelLine: {
                    normal: {
                        show: true
                    }
                },
                data:<?=json_encode($political)?>
            }
        ]
    };
    myChart.setOption(option);


    var myChart = echarts.init(document.getElementById('main1'));
    option = {
        tooltip: {
            trigger: 'item',
            show:false
//            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'left',
            left:'10',
            top:'15',
            data:['男','女'],
            itemGap:10,
            itemHeight: 15,
            itemWidth: 15

        },

        color:['#9fe6f5','#fc6e6a'],
        series: [
            {
                name:'访问来源',
                type:'pie',
                radius: ['27%', '53%'],
                center:['56%','60%'],
                avoidLabelOverlap: false,

                label: {
                    normal: {
                        formatter: '{d}%',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        shadowBlur:3,
                        shadowOffsetX: 2,
                        shadowOffsetY: 2,
                        shadowColor: '#999',
                        padding: [0, 7],
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            abg: {
                                backgroundColor: '#333',
                                width: '100%',
                                align: 'right',
                                height: 22,
                                borderRadius: [4, 4, 0, 0]
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }

                },

                labelLine: {
                    normal: {
                        show: true
                    }
                },
                data:[
                    {value:<?=$gender[1]?>, name:'男'},
                    {value:<?=$gender[2]?>, name:'女'}

                ]
            }
        ]
    };


    myChart.setOption(option);



    var myChart = echarts.init(document.getElementById('main2'));
    option = {
        tooltip: {
            trigger: 'item',
            show:false
//            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'left',
            left:'10',
            top:'15',
            data:['20-30岁','30-40岁','40-50岁','50-60岁','60岁以上'],
            itemGap:10,
            itemHeight: 15,
            itemWidth: 15

        },



        color:['#93e1f2','#ffe183','#fdaeca','#91cea5','#f96b68'],
        series: [
            {
                name:'访问来源',
                type:'pie',
                radius: ['25%', '50%'],
                center:['56%','68%'],
                avoidLabelOverlap: false,



                label: {
                    normal: {
                        formatter: '{d}%',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        shadowBlur:3,
                        shadowOffsetX: 2,
                        shadowOffsetY: 2,
                        shadowColor: '#999',
                        padding: [0, 7],
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            abg: {
                                backgroundColor: '#333',
                                width: '100%',
                                align: 'right',
                                height: 22,
                                borderRadius: [4, 4, 0, 0]
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }

                },

                labelLine: {
                    normal: {
                        show: true
                    }
                },
                data: <?=json_encode($age)?>
            }
        ]
    };
    myChart.setOption(option);



	
	var names = [];
	<?php if($endcation){?>
		<?php foreach($endcation as $val){?>
			names.push('<?=$val["name"]?>');
		<?php }?>
	<?php }?>

    var myChart = echarts.init(document.getElementById('main3'));
    option = {
        tooltip: {
            trigger: 'item',
            show:false
//            formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
            orient: 'vertical',
            x: 'left',
            left:'10',
            top:'15',
            data:names,
            itemGap:10,
            itemHeight: 15,
            itemWidth: 15

        },



        color:['#93e1f2','#ffe183','#fdaeca','#91cea5','#f96b68','#f08afc'],
        series: [
            {
                name:'访问来源',
                type:'pie',
                radius: ['25%', '50%'],
                center:['56%','68%'],
                avoidLabelOverlap: false,



                label: {
                    normal: {
                        formatter: '{d}%',
                        backgroundColor: '#eee',
                        borderColor: '#aaa',
                        borderWidth: 1,
                        borderRadius: 4,
                        shadowBlur:3,
                        shadowOffsetX: 2,
                        shadowOffsetY: 2,
                        shadowColor: '#999',
                        padding: [0, 7],
                        rich: {
                            a: {
                                color: '#999',
                                lineHeight: 22,
                                align: 'center'
                            },
                            abg: {
                                backgroundColor: '#333',
                                width: '100%',
                                align: 'right',
                                height: 22,
                                borderRadius: [4, 4, 0, 0]
                            },
                            hr: {
                                borderColor: '#aaa',
                                width: '100%',
                                borderWidth: 0.5,
                                height: 0
                            },
                            b: {
                                fontSize: 16,
                                lineHeight: 33
                            },
                            per: {
                                color: '#eee',
                                backgroundColor: '#334455',
                                padding: [2, 4],
                                borderRadius: 2
                            }
                        }
                    }

                },

                labelLine: {
                    normal: {
                        show: true
                    }
                },
                data: <?=json_encode($endcation)?>
            }
        ]
    };
    myChart.setOption(option);
</script>

</body>
</html>
