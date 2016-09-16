<?php

$limit_per_page = 30;

if(isset($_REQUEST['page'])) {

	$page = $_REQUEST['page'];

	$starting_point = $page * $limit_per_page;
	$end_point = $starting_point + $limit_per_page;
}
else {

	$page = 0;

	$starting_point = 0;
	$end_point = $limit_per_page;
}

include_once('settings.php');
include_once('functions.php');

$list = makeSmiley('','codes');
$allKeys = array_keys($list);

$total = count($list);

//if($end_point < $total) {

	//print '<style>td{border:1px solid #ccc;text-align:center;}</style><table>';

	for($i = $starting_point; $i <= $end_point; $i++) {

		if($i < $total) {

			$code = $allKeys[$i];
			if(strlen($code) >0) {
				$image = $list[$code];

				print '<tr><td>'.$code.'</td><td>'.$image.'</td></tr>';
			}
		}
	}

	//print '</table>';

	print '<div style="margin:20.5% 0 2.5% 0;text-align:center;">';

	if($starting_point != 0) {
		print '<a href="emoticons.php?page='.($page-1).'">Prev</a> - ';
	}

	$nt = round($total / $limit_per_page);

	print '<a href="emoticons.php?page='.($page+1).'">Next</a>';

	print '<form style="margin-top:0.5%;" class="emojump">Jump to page : <select name="jump_page_no" class="jump_page_no"><option value="none">None</option>';
	for($i=0;$i<=$nt;$i++) {
		print '<option value="'.$i.'">'.$i.'</option>';
	}
	print '</select></form></div>';
//}

?>