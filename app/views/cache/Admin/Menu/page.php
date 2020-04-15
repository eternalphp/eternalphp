
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>t h e m e l o c k . c o m</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/minified/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/minified/core.min.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/minified/components.min.css" rel="stylesheet" type="text/css">
	<link href="/assets/css/minified/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="/assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="/assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="/assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/loaders/blockui.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/ui/nicescroll.min.js"></script>
	<script type="text/javascript" src="/assets/js/plugins/ui/drilldown.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script type="text/javascript" src="/assets/js/core/app.js"></script>
	<!-- /theme JS files -->

</head>

<body>

	<!-- Page header -->
	<div class="page-header">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Tables</span> - Basic</h4>

				<ul class="breadcrumb breadcrumb-caret position-right">
					<li><a href="index.html">Home</a></li>
					<li><a href="table_basic.html">Tables</a></li>
					<li class="active">Basic</li>
				</ul>
			</div>

			<div class="heading-elements">
				<div class="heading-btn-group">
					<a href="#" class="btn btn-link btn-float has-text"><i class="icon-bars-alt text-primary"></i><span>Statistics</span></a>
					<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calculator text-primary"></i> <span>Invoices</span></a>
					<a href="#" class="btn btn-link btn-float has-text"><i class="icon-calendar5 text-primary"></i> <span>Schedule</span></a>
				</div>
			</div>
		</div>
	</div>
	<!-- /page header -->


			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Basic table -->
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Basic table</h5>
						<div class="heading-elements">
							<ul class="icons-list">
		                		<li><a data-action="collapse"></a></li>
		                		<li><a data-action="reload"></a></li>
		                		<li><a data-action="close"></a></li>
		                	</ul>
	                	</div>
					<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

					<div class="panel-body">
						Example of a <code>basic</code> table. For basic styling (light padding and only horizontal dividers) add the base class <code>.table</code> to any <code>&lt;table&gt;</code>. It may seem super redundant, but given the widespread use of tables for other plugins like calendars and date pickers, we've opted to isolate our custom table styles.
					</div>

					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Username</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Eugene</td>
									<td>Kopyov</td>
									<td>@Kopyov</td>
								</tr>
								<tr>
									<td>2</td>
									<td>Victoria</td>
									<td>Baker</td>
									<td>@Vicky</td>
								</tr>
								<tr>
									<td>3</td>
									<td>James</td>
									<td>Alexander</td>
									<td>@Alex</td>
								</tr>
								<tr>
									<td>4</td>
									<td>Franklin</td>
									<td>Morrison</td>
									<td>@Frank</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /basic table -->


				<!-- Striped rows -->
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Striped rows</h5>
						<div class="heading-elements">
							<ul class="icons-list">
		                		<li><a data-action="collapse"></a></li>
		                		<li><a data-action="reload"></a></li>
		                		<li><a data-action="close"></a></li>
		                	</ul>
	                	</div>
					<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

					<div class="panel-body">
						Example of a table with <code>striped</code> rows. Use <code>.table-striped</code> added to the base <code>.table</code> class to add zebra-striping to any table odd row within the <code>&lt;tbody&gt;</code>. This styling doesn't work in IE8 and lower as <code>:nth-child</code> CSS selector isn't supported in these browser versions. Striped table can be combined with other table styles.
					</div>

					<div class="table-responsive">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Username</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Eugene</td>
									<td>Kopyov</td>
									<td>@Kopyov</td>
								</tr>
								<tr>
									<td>2</td>
									<td>Victoria</td>
									<td>Baker</td>
									<td>@Vicky</td>
								</tr>
								<tr>
									<td>3</td>
									<td>James</td>
									<td>Alexander</td>
									<td>@Alex</td>
								</tr>
								<tr>
									<td>4</td>
									<td>Franklin</td>
									<td>Morrison</td>
									<td>@Frank</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /striped rows -->


				<!-- Bordered table -->
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Bordered table</h5>
						<div class="heading-elements">
							<ul class="icons-list">
		                		<li><a data-action="collapse"></a></li>
		                		<li><a data-action="reload"></a></li>
		                		<li><a data-action="close"></a></li>
		                	</ul>
	                	</div>
					<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

					<div class="panel-body">
						Example of a table with fully <code>bordered</code> cells. Add <code>.table-bordered</code> to the base <code>.table</code> class for borders on all sides of the table and cells. This is a default Bootstrap option for the table, for more advanced border options check <a href="table_borders.html">Table borders</a> page. Bordered table can be combined with other table styles.
					</div>

					<div class="table-responsive">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>#</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Username</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Eugene</td>
									<td>Kopyov</td>
									<td>@Kopyov</td>
								</tr>
								<tr>
									<td>2</td>
									<td>Victoria</td>
									<td>Baker</td>
									<td>@Vicky</td>
								</tr>
								<tr>
									<td>3</td>
									<td>James</td>
									<td>Alexander</td>
									<td>@Alex</td>
								</tr>
								<tr>
									<td>4</td>
									<td>Franklin</td>
									<td>Morrison</td>
									<td>@Frank</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /bordered table -->


				<!-- Bordered striped table -->
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Bordered striped</h5>
						<div class="heading-elements">
							<ul class="icons-list">
		                		<li><a data-action="collapse"></a></li>
		                		<li><a data-action="reload"></a></li>
		                		<li><a data-action="close"></a></li>
		                	</ul>
	                	</div>
					<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

					<div class="panel-body">
						Example of a <code>bordered</code> table with <code>stiped</code> rows. Add <code>.table-bordered</code> and <code>.table-striped</code> classes to the base <code>.table</code> class for borders and row striping. This method works with all table border options.
					</div>

					<div class="table-responsive">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Username</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Eugene</td>
									<td>Kopyov</td>
									<td>@Kopyov</td>
								</tr>
								<tr>
									<td>2</td>
									<td>Victoria</td>
									<td>Baker</td>
									<td>@Vicky</td>
								</tr>
								<tr>
									<td>3</td>
									<td>James</td>
									<td>Alexander</td>
									<td>@Alex</td>
								</tr>
								<tr>
									<td>4</td>
									<td>Franklin</td>
									<td>Morrison</td>
									<td>@Frank</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /bordered striped table -->


				<!-- Hover rows -->
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Hover rows</h5>
						<div class="heading-elements">
							<ul class="icons-list">
		                		<li><a data-action="collapse"></a></li>
		                		<li><a data-action="reload"></a></li>
		                		<li><a data-action="close"></a></li>
		                	</ul>
	                	</div>
					<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

					<div class="panel-body">
						Example of a table with <code>hover</code> rows state. Add <code>.table-hover</code> to enable a hover state on table rows within a <code>&lt;tbody&gt;</code>.
					</div>

					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Username</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Eugene</td>
									<td>Kopyov</td>
									<td>@Kopyov</td>
								</tr>
								<tr>
									<td>2</td>
									<td>Victoria</td>
									<td>Baker</td>
									<td>@Vicky</td>
								</tr>
								<tr>
									<td>3</td>
									<td>James</td>
									<td>Alexander</td>
									<td>@Alex</td>
								</tr>
								<tr>
									<td>4</td>
									<td>Franklin</td>
									<td>Morrison</td>
									<td>@Frank</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /hover rows -->


				<!-- Scrollable table -->
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Scrollable table</h5>
						<div class="heading-elements">
							<ul class="icons-list">
		                		<li><a data-action="collapse"></a></li>
		                		<li><a data-action="reload"></a></li>
		                		<li><a data-action="close"></a></li>
		                	</ul>
	                	</div>
					<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

					<div class="panel-body">
						Example of a <code>scrollable</code> table. To use a fixed height with scrolling, wrap any table in a div with <code>.pre-scrollable</code> class. Max height of the table container will be <code>340px</code> and table will get a vertical scrollbar if its height is move than this value.
					</div>

					<div class="table-responsive pre-scrollable">
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>First Name</th>
									<th>Last Name</th>
									<th>Username</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Eugene</td>
									<td>Kopyov</td>
									<td>@Kopyov</td>
								</tr>
								<tr>
									<td>2</td>
									<td>Victoria</td>
									<td>Baker</td>
									<td>@Vicky</td>
								</tr>
								<tr>
									<td>3</td>
									<td>James</td>
									<td>Alexander</td>
									<td>@Alex</td>
								</tr>
								<tr>
									<td>4</td>
									<td>Stanley</td>
									<td>Martins</td>
									<td>@Stan</td>
								</tr>
								<tr>
									<td>5</td>
									<td>Winnie</td>
									<td>the Pooh</td>
									<td>@Winnie</td>
								</tr>
								<tr>
									<td>6</td>
									<td>Garry</td>
									<td>Smith</td>
									<td>@Garry</td>
								</tr>
								<tr>
									<td>7</td>
									<td>Ian</td>
									<td>Berg</td>
									<td>@Ian</td>
								</tr>
								<tr>
									<td>8</td>
									<td>John</td>
									<td>Ryan</td>
									<td>@John</td>
								</tr>
								<tr>
									<td>9</td>
									<td>Frank</td>
									<td>Giggs</td>
									<td>@Frank</td>
								</tr>
								<tr>
									<td>10</td>
									<td>Jack</td>
									<td>Gram</td>
									<td>@Jack</td>
								</tr>
								<tr>
									<td>11</td>
									<td>Patrick</td>
									<td>Lawrence</td>
									<td>@Patrick</td>
								</tr>
								<tr>
									<td>12</td>
									<td>Lucy</td>
									<td>Gulf</td>
									<td>@Lucy</td>
								</tr>
								<tr>
									<td>13</td>
									<td>Dave</td>
									<td>Armstrong</td>
									<td>@Dave</td>
								</tr>
								<tr>
									<td>14</td>
									<td>Sean</td>
									<td>Lewis</td>
									<td>@Sean</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- /scrollable table -->



				<!-- Panel body tables title -->
				<h6 class="content-group text-semibold">
					Panel body tables
					<small class="display-block">Tables placed inside <code>panel body</code>.</small>
				</h6>
				<!-- /panel body tables title -->


				<!-- Panel body table -->
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Panel body table</h5>
						<div class="heading-elements">
							<ul class="icons-list">
		                		<li><a data-action="collapse"></a></li>
		                		<li><a data-action="reload"></a></li>
		                		<li><a data-action="close"></a></li>
		                	</ul>
	                	</div>
					<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

					<div class="panel-body">
						<p class="content-group">Example of a table placed inside <code>panel body</code>. Such tables always have additional whitespace taken from <code>.panel-body</code> element padding.</p>

						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>#</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Username</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Eugene</td>
										<td>Kopyov</td>
										<td>@Kopyov</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Victoria</td>
										<td>Baker</td>
										<td>@Vicky</td>
									</tr>
									<tr>
										<td>3</td>
										<td>James</td>
										<td>Alexander</td>
										<td>@Alex</td>
									</tr>
									<tr>
										<td>4</td>
										<td>Franklin</td>
										<td>Morrison</td>
										<td>@Frank</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /panel body table -->


				<!-- Framed panel body table -->
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Framed table</h5>
						<div class="heading-elements">
							<ul class="icons-list">
		                		<li><a data-action="collapse"></a></li>
		                		<li><a data-action="reload"></a></li>
		                		<li><a data-action="close"></a></li>
		                	</ul>
	                	</div>
					<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

					<div class="panel-body">
						<p class="content-group">Example of <code>framed</code> table inside panel body. Tables that placed inside panel body don't have border around them by default, <code>.table-framed</code> class adds border around the table.</p>

						<div class="table-responsive">
							<table class="table table-framed">
								<thead>
									<tr>
										<th>#</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Username</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Eugene</td>
										<td>Kopyov</td>
										<td>@Kopyov</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Victoria</td>
										<td>Baker</td>
										<td>@Vicky</td>
									</tr>
									<tr>
										<td>3</td>
										<td>James</td>
										<td>Alexander</td>
										<td>@Alex</td>
									</tr>
									<tr>
										<td>4</td>
										<td>Franklin</td>
										<td>Morrison</td>
										<td>@Frank</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /framed panel body table -->


				<!-- Bordered panel body table -->
				<div class="panel panel-flat">
					<div class="panel-heading">
						<h5 class="panel-title">Framed bordered</h5>
						<div class="heading-elements">
							<ul class="icons-list">
		                		<li><a data-action="collapse"></a></li>
		                		<li><a data-action="reload"></a></li>
		                		<li><a data-action="close"></a></li>
		                	</ul>
	                	</div>
					<a class="heading-elements-toggle"><i class="icon-menu"></i></a></div>

					<div class="panel-body">
						<p class="content-group">Example of <code>bordered framed</code> table inside panel body. By default bordered table also doesn't have a border, to use border around the bordered table add <code>.table-framed</code> to the <code>&lt;table&gt;</code>.</p>

						<div class="table-responsive">
							<table class="table table-bordered table-framed">
								<thead>
									<tr>
										<th>#</th>
										<th>First Name</th>
										<th>Last Name</th>
										<th>Username</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td>Eugene</td>
										<td>Kopyov</td>
										<td>@Kopyov</td>
									</tr>
									<tr>
										<td>2</td>
										<td>Victoria</td>
										<td>Baker</td>
										<td>@Vicky</td>
									</tr>
									<tr>
										<td>3</td>
										<td>James</td>
										<td>Alexander</td>
										<td>@Alex</td>
									</tr>
									<tr>
										<td>4</td>
										<td>Franklin</td>
										<td>Morrison</td>
										<td>@Frank</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<!-- /bordered panel body table -->

			</div>
			<!-- /main content -->

</body>
</html>
