<?php


/*	author: vipin bathaw
	year: 2012
	licence: gpl
	note: you are allowed to modify script, but selling is prohibited.
*/

date_default_timezone_set('asia/kolkata');

require_once('settings.php');


function update($id) {
	$recent = date("U");
	mysql_query("update members set recent='".$recent."' where id='".$id."'") or die(mysql_error());

	$laston = date("D j M Y g:ia");
	mysql_query("update members set lastonline='".$laston."' where id='".$id."'") or die(mysql_error());
	
	mysql_query("update members set online='1' where id='".$id."'") or die(mysql_error());
}

function shout($shouter,$message) {
	
	$message = commentsfilter(trim($message));
	$time = date("D g:ia");
	
	if(strlen($message) > 0) {
		mysql_query("insert into shouts (shouter,message,time) values ('$shouter','$message','$time')") or die(mysql_error());
	}
	
}

function getshouts() {

	
	
	$row = mysql_query("select * from shouts order by id desc limit 15") or die(mysql_error());
	while($shouts = mysql_fetch_array($row)) {
		
		$shouter_id = $shouts['shouter'];
		$message = stripslashes(makesmiley($shouts['message'],'normal'));
		
		$shouter2 = mysql_query("select * from members where id='".$shouter_id."'") or die(mysql_error());
		$user = mysql_fetch_array($shouter2);
		
		print'
		<li class="shouts"><div id="shouter"><a href="profile.php?id='.$user['id'].'&ref=shoutbox.php">'.$user['name'].'</a><span>['.$shouts['time'].']</span></div><div class="message">'.$message.'</div></li>';
		
	}
	
}

function get_unread_pm($userid) {
	$a1 = mysql_query("select COUNT(id) as count from pm where receiver=".$userid." and viewed='0'");
	$a2 = mysql_fetch_array($a1);
	return $a2['count'];
}

function get_unread_and_undelivered_pm($userid) {
	$a1 = mysql_query("select COUNT(id) as count from pm where receiver=".$userid." and delivered='0'");
	$a2 = mysql_fetch_array($a1);
	return $a2['count'];
}


function get_online_buddies($userid) {
	$i = 0;
	$k = date("U")-180;
	$active = mysql_query("select * from members where recent>'$k' and online='1'") or die(mysql_error());
	while($actives = mysql_fetch_array($active)) {
		$list_person = $actives['id'];
		$tte = mysql_query("select * from buddylist where receiver='".$userid."' and sender='".$list_person."' and accepted='1'") or die(mysql_error());
		$num = mysql_num_rows($tte);
		if($num == '0') {
			$tte2 = mysql_query("select * from buddylist where receiver='".$list_person."' and sender='".$userid."' and accepted='1'") or die(mysql_error());
			$num1 = mysql_num_rows($tte2);
			if($num1 > 0) {
				$i++;
			}
		}
		else {
			$i++;
		}
	}
	return $i;
}


function makesmiley($text,$para) {

	$codes = array(
		
		':blockout:' => "<img src='images/s/blockout.gif' alt='blockout'>",
		':dance:' => "<img src='images/s/dance.gif' alt='dance'>", 
		':devil:' => "<img src='images/s/devil.gif' alt='devil'>",
		':lmao:' => "<img src='images/s/lmao.gif' alt='lmao'>", 
		':love:' => "<img src='images/s/love.gif' alt='love'>", 
		':bye:' => "<img src='images/s/bye.gif' alt='bye'>",
		':angry:' => "<img src='images/s/angry.gif' alt='angry'>", 
		':goodjob:' => "<img src='images/s/goodjob.gif' alt='goodjob'>", 
		':no:' => "<img src='images/s/no.gif' alt='no'>", 
		 
		
		':looser:' => "<img src='images/s/looser.gif' alt='looser'>", 
		':crazy:' => "<img src='images/s/crazy.gif' alt='crazy'>", 
		':argue:' => "<img src='images/s/argue.gif' alt='argue'>", 
		':crazydance:' => "<img src='images/s/crazydance.gif' alt='crazydance'>",
		':hug:' => "<img src='images/s/hug.gif' alt='hug'>", 
		':ninja:' => "<img src='images/s/ninja.gif' alt='ninja'>", 
		
		':hyperhug:' => "<img src='images/s/hyperhug.gif' alt='hyperhug'>", 
		':drunk:' => "<img src='images/s/drunk.gif' alt='drunk'>", 
		':punish:' => "<img src='images/s/punish.gif' alt='punish'>", 
		':urock:' => "<img src='images/s/urock.gif' alt='urock'>", 
		':haha:' => "<img src='images/s/haha.gif' alt='haha'>",
		':rockon:' => "<img src='images/s/rockon.gif' alt='rockon'>", 
		':clap1:' => "<img src='images/s/clap1.gif' alt='clap1'>", 
		
		':plane:' => "<img src='images/s/plane.gif' alt='plane'>", 
		
		':bhangra:' => "<img src='images/s/bhangra.gif' alt='bhangra'>", 
		':inlove:' => "<img src='images/s/inlove.gif' alt='inlove'>", 
		':cry:' => "<img src='images/s/cry.gif'>", 
		
		':laughing:' => "<img src='images/s/laughing.gif'>", 
		':rofl:' => "<img src='images/s/laughing.gif'>", 
		':please:' => "<img src='images/s/please.gif'>", 
		':hang:' => "<img src='images/s/hang.gif'>", 
		':1st:' => "<img src='images/s/1st.gif'>", 
		':worthy:' => "<img src='images/s/worthy.gif'>", 
		':wink:' => "<img src='images/s/wink.gif'>",
		':tease:' => "<img src='images/s/tease.gif'>", 
		
		':3rd:' => "<img src='images/s/3rd.gif'>", 
		':victory:' => "<img src='images/s/victory.gif' alt='victory'>", 
		':bravo:' => "<img src='images/s/bravo.gif' alt='bravo'>", 
		':geek:' => "<img src='images/s/geek.gif' alt='geek'>", 
		':bb:' => "<img src='images/s/bb.gif' alt='bb'>", 
		':stop:' => "<img src='images/s/stop.gif' alt='stop'>", 
		':great:' => "<img src='images/s/great.gif' alt='great'>", 
		':hehe:' => "<img src='images/s/hehe.gif' alt='hehe'>", 
		':dog:' => "<img src='images/s/dog.gif' alt='dog'>",
		':thanks:' => "<img src='images/s/thanks.gif' alt='thanks'>",
		':shake:' => "<img src='images/s/shake.gif' alt='shake'>", 
		':sad:' => "<img src='images/s/sad.gif' alt='sad'>", 
		':rolling:' => "<img src='images/s/rolling.gif' alt='rolling'>",
		':crying:' => "<img src='images/s/crying.gif'>",
		':shh:' => "<img src='images/s/shh.gif'>", 
		':like:' => "<img src='images/s/like.gif'>", 
		':boxing:' => "<img src='images/s/boxing.gif' alt='boxing'>",
		':wub:' => "<img src='images/s/wub.gif'>",
		':watermelon:' => "<img src='images/s/watermelon.gif'>", 
		':blastoff:' => "<img src='images/s/blastoff.gif'>", 
		':tongue:' => "<img src='images/s/tongue.gif'>",
		':hungry:' => "<img src='images/s/hungry.gif'>",
		':('=> "<img src='images/s/sad.gif'>", 

		':unsure:' => "<img src='images/s/unsure.gif' alt='unsure'>", 
		':mad:' => "<img src='images/s/mad.gif' alt='mad'>", 
		':danda:' => "<img src='images/s/danda.gif' alt='danda'>",  
		':laugh:' => "<img src='images/s/laugh.gif' alt='laugh'>", 
		':yahoo:' => "<img src='images/s/yahoo.gif' alt='yahoo'>", 
		':confused:' => "<img src='images/s/confused.gif' alt='confused'>",
		':lol2:' => "<img src='images/s/lol2.gif'>",
		':wwe:' => "<img src='images/s/wwe.gif'>",
		':cool:' => "<img src='images/s/cool.gif'>", 
		':woo:' => "<img src='images/s/woo.gif'>", 
		
		':sorry:' => "<img src='images/s/sorry.gif'>", 
		':getout:' => "<img src='images/s/getout.gif'>", 
		':dj:' => "<img src='images/s/dj.gif'>",
		':birthday:' => "<img src='images/s/birthday.gif'>", 
		':welcome:' => "<img src='images/s/welcome.gif'>", 
		':suprise:' => "<img src='images/s/suprise.gif'>", 
		':santa:' => "<img src='images/s/santa.gif'>", 
		':kiss:' => "<img src='images/s/kiss.gif'>",
		':o' => "<img src='images/s/o.gif'>", 
		':o' => "<img src='images/s/o.gif'>", 
		':fight:' => "<img src='images/s/fight.gif'>",
		':yu:' => "<img src='images/s/yu.gif'>", 
		':megashock:' => "<img src='images/s/megashock.gif'>", 
		':)' => "<img src='images/s/smile.gif'>", 
		':friends:' => "<img src='images/s/friends.gif'>", 
		':bang:' => "<img src='images/s/bang.gif'>",
		':lol:' => "<img src='images/s/lol.gif'>", 
		':broken:' => "<img src='images/s/broken.gif'>", 
		':help:' => "<img src='images/s/help.gif'>", 
		':2nd:' => "<img src='images/s/2nd.gif'>", 
		':india:' => "<img src='images/s/india.gif'>", 
		':what:' => "<img src='images/s/what.gif'>", 
		':boo:' => "<img src='images/s/boo.gif'>",
		':ham:' => "<img src='images/s/ham.gif'>", 
		':has:' => "<img src='images/s/has.gif' alt='has'>", 
		':hi:' => "<img src='images/s/hi.gif'>", 
		':p:' => "<img src='images/s/p.gif'>", 
		':band:' => "<img src='images/s/band.gif'>", 
		':wow:' => "<img src='images/s/wow.gif'>", 
		':evil2:' => "<img src='images/s/evil2.gif'>", 
		':yawn:' => "<img src='images/s/yawn.gif'>",
		':innocent:' => "<img src='images/s/innocent.gif'>", 
		':clap:' => "<img src='images/s/clap.gif'>",
		':nail:' => "<img src='images/s/nail.gif'>",
		':punish1:' => "<img src='images/s/punish1.gif'>", 
		':kiss1:' => "<img src='images/s/kiss1.gif'>", 
		':yeh:' => "<img src='images/s/yeh.gif'>", 
		':snoozer:' => "<img src='images/s/snoozer.gif'>", 
		':budo:' => "<img src='images/s/budo.gif'>", 
		':cry3:' => "<img src='images/s/cry3.gif'>", 
		':lol1:' => "<img src='images/s/lol1.gif'>",
		':ohyes:' => "<img src='images/s/ohyes.gif' alt='ohyes' alt='ohyes'>",
		':money:' => "<img src='images/s/money.gif'  alt='money'>",
		':shit:' => "<img src='images/s/shit.gif' alt='shit'>",
		':lau2:' => "<img src='images/s/lau2.gif' alt='lau2'>",
		':lau1:' => "<img src='images/s/lau1.gif' alt='lau1'>",
		':dance123:' => "<img src='images/s/dance123.gif' alt='dance123'>",
		':bye1:' => "<img src='images/s/bye1.gif' alt='bye1'>",
		':wow1:' => "<img src='images/s/wow1.gif' alt='wow1'>",
		':lau3:' => "<img src='images/s/lau3.gif' alt='lau3'>",    
		':clean:' => "<img src='images/s/clean.gif' alt='clean'>",
		':baby3:' => "<img src='images/s/baby3.gif' alt='baby3'>",
		':rockstar:' => "<img src='images/s/rockstar.gif'>",
		':superman2:' => "<img src='images/s/superman2.gif'>", 
		':loveu:' => "<img src='images/s/loveu.gif' alt='loveu'>",
		':simba:' => "<img src='images/s/simba.gif' alt='samba'>",
		':gm1:' => "<img src='images/s/gm1.gif' alt='gm1'>",
		':gm2:' => "<img src='images/s/gm2.gif' alt='gm2'>",
		':play:' => "<img src='images/s/play.gif' alt='play'>",
		':omg:' => "<img src='images/s/omg.gif' alt='omg'>", 
		':fishing:' => "<img src='images/s/fishing.gif' alt='fishing'>",
		':flood:' => "<img src='images/s/flood.gif' alt='flood'>", 
		':phool:' => "<img src='images/s/phool.gif' alt='phool'>", 
		':bemine:' => "<img src='images/s/bemine.gif' alt='bemine'>", 
		':cry1:' => "<img src='images/s/cry1.gif' alt='cry1'>", 
		':triangle:' => "<img src='images/s/hangout.gif' alt='triangle'>", 
		':hahaha:' => "<img src='images/s/hahaha.gif' alt='hahaha'>", 
		':bighug:' => "<img src='images/s/bighug.gif' alt='bighug'>", 
		
		':adv:' => "<img src='images/s/adv.gif' alt='adv'>", 
		':pissed:' => "<img src='images/s/pissed.gif' alt='pissed'>", 
		':aaila:' => "<img src='images/s/aaila.gif' alt='aaila'>",  
		':laser:' => "<img src='images/s/laser.gif' alt='laser'>", 
		':monkey:' => "<img src='images/s/monkey.gif' alt='monkey'>", 
		':dumped:' => "<img src='images/s/dumped.gif' alt='dumped'>",  
		':dead:' => "<img src='images/s/dead.gif' alt='dead'>", 
		':party:' => "<img src='images/s/party.gif' alt='party'>", 
		':wwe1:' => "<img src='images/s/wwe1.gif' alt='wwe1'>", 
		':mod:' => "<img src='images/s/mod.gif' alt='mod'>", 
		':newbie:' => "<img src='images/s/newbie.gif' alt='newbie'>", 
		':bday:' => "<img src='images/s/bday.gif'>",
		':doublekiss:' => "<img src='images/s/doublekiss.gif'>", 
		':shy:' => "<img src='images/s/shy.gif'>", 
		':vodka1:' => "<img src='images/s/vodka1.gif'>",  
		':coolguy:' => "<img src='images/s/coolguy.gif'>", 
		':potty:' => "<img src='images/s/potty.gif'>", 
		':cigwar:' => "<img src='images/s/cigwar.gif'>", 
		':whistle:' => "<img src='images/s/whistle.gif'>", 
		':lau:' => "<img src='images/s/lau.gif'>", 
		':d' => "<img src='images/s/d.gif'>", 
		':goodnight:' => "<img src='images/s/goodnight.gif'>",  
		':ban:' => "<img src='images/s/ban.gif'>",
		':wondering:' => "<img src='images/s/wondering.gif'>", 
		':bye1:' => "<img src='images/s/bye1.gif'>", 
		':dance2:' => "<img src='images/s/dance2.gif'>", 
		':yessir:' => "<img src='images/s/yessir.gif'>", 
		':baby:' => "<img src='images/s/baby.gif'>", 
		':cute:' => "<img src='images/s/monkey63.gif'>",
		':silly:' => "<img src='images/s/manga24.gif'>", 
		':kamine:' => "<img src='images/s/kamine.png'>", 
		':hehi:' => "<img src='images/s/hehi.gif'>", 
		':shock:' => "<img src='images/s/shock.gif'>", 
		':khao:' => "<img src='images/s/meatballs.gif'>", 
		':brush:' => "<img src='images/s/brush.gif'>", 
		':closed:' => "<img src='images/s/closed.gif'>", 
		':bore:' => "<img src='images/s/bore.gif'>",
		':drum1:' => "<img src='images/s/drum1.gif'>", 
		':pm:' => "<img src='images/s/pm.gif'>",
		':o:' => "<img src='images/s/baby11.gif'>",  
		':flower:' => "<img src='images/s/pig017.gif'>", 
		':l:' => "<img src='images/s/l.gif'>", 
		':cry2:' => "<img src='images/s/cry2.gif'>",
		':door:' => "<img src='images/s/door.gif'>", 
		':yele:' => "<img src='images/s/yele.gif'>", 
		':ooo:' => "<img src='images/s/o.gif'>", 
		':oo:' => "<img src='images/s/oo.gif'>", 
		':71:' => "<img src='images/s/71.gif'>", 
		':duh:' => "<img src='images/s/duh.gif'>",
		':lazy:' => "<img src='images/s/lazy.gif' alt='lazy'>",
		':jhula:' => "<img src='images/s/jhula.gif'>", 
		':excited:' => "<img src='images/s/excited.gif' alt='excited'>", 
		':danti:' => "<img src='images/s/danti.gif'>", 
		':earlock:' => "<img src='images/s/earlock.gif'>", 
		':out:' => "<img src='images/s/out.gif'>", 
		':cycle:' => "<img src='images/s/cycle.gif'>", 
		':pc:' => "<img src='images/s/pc.gif'>", 
		':laugha:' => "<img src='images/s/laugha.gif'>", 
		':gulel:' => "<img src='images/s/gulel.gif'>", 
		':yele1:' => "<img src='images/s/yele1.gif'>", 
		':tohfa:' => "<img src='images/s/tohfa.gif'>",
		':alien:' => "<img src='images/s/alien.gif'>", 
		':hit1:' => "<img src='images/s/hit1.gif'>", 
		':repost:' => "<img src='images/s/repost.gif'>", 
		':cake:' => "<img src='images/s/cake.gif'>", 
		':brush1:' => "<img src='images/s/brush1.gif'>", 
		':carry:' => "<img src='images/s/carry.gif'>", 
		':crazydrive:' => "<img src='images/s/crazydrive.gif'>", 
		':dancer:' => "<img src='images/s/dancer.gif'>", 
		':guitarlove:' => "<img src='images/s/guitarlove.gif'>", 
		':love4ever:' => "<img src='images/s/love4ever.gif'>", 
		':rainlove:' => "<img src='images/s/rainlove.gif'>", 
		':rockrules:' => "<img src='images/s/rockrules.gif'>", 
		':shawe:' => "<img src='images/s/shawe.gif'>", 
		':shawing:' => "<img src='images/s/shawing.gif'>", 
		':stalker:' => "<img src='images/s/stalker.gif'>", 
		':volleyball:' => "<img src='images/s/volleyball.gif'>", 
		':beta1:' => "<img src='images/s/beta1.gif'>", 
		':beta:' => "<img src='images/s/beta.gif'>",
		':pirate:' => "<img src='images/s/pirate.gif'>", 
		':tennis:' => "<img src='images/s/tennis.gif'>",  
		':shilpa:' => "<img src='images/s/shilpa.gif'>",
		':clap22:' => "<img src='images/s/icon39.gif'>", 
		':dontknow:' => "<img src='images/s/dontknow.gif'>", 
		
		':jump:' => "<img src='images/s/jump.gif'>", 
		':music:' => "<img src='images/s/music.gif'>", 
		':p:' => "<img src='images/s/lele.gif'>",
		':bleh:' => "<img src='images/s/bleh.gif'>", 
		':mtgun:' => "<img src='images/s/mtgun.gif'>", 
		':gabbar:' => "<img src='images/s/gabbar.gif'>", 
		':matrix:' => "<img src='images/s/matrix.gif'>", 
		':lolly:' => "<img src='images/s/lolly.gif'>",
		':nude:' => "<img src='images/s/nude.gif'>", 
		':sad3:' => "<img src='images/s/sad3.gif'>", 
		':wave:' => "<img src='images/s/wave.gif'>", 
		':hatsoff:' => "<img src='images/s/hatsoff.gif'>", 
		':bhaloo:' => "<img src='images/s/bhaloo.gif'>", 
		':cola:' => "<img src='images/s/cola.jpg'>",
		':loading:' => "<img src='images/s/loading.gif'>", 
		':dhoom:' => "<img src='images/s/dhoom.gif'>", 
		':urva:' => "<img src='images/s/urva.gif'>", 

		':ashamed3:'=>"<img src='images/s/ashamed0005.gif'>",
		':ashamed4:'=>"<img src='images/s/ashamed0006.gif'>",
		':character1:'=>"<img src='images/s/character0001.gif'>",
		':character2:'=>"<img src='images/s/character0002.gif'>",
		':character3:'=>"<img src='images/s/character0003.gif'>",
		':character4:'=>"<img src='images/s/character0004.gif'>",
		':character5:'=>"<img src='images/s/character0007.gif'>",
		':character6:'=>"<img src='images/s/character0008.gif'>",
		':character7:'=>"<img src='images/s/character0009.gif'>",
		':cigy:'=>"<img src='images/s/character0011.gif'>",
		':character8:'=>"<img src='images/s/character0012.gif'>",
		':character9:'=>"<img src='images/s/character0013.gif'>",
		':character10:'=>"<img src='images/s/character0022.gif'>",
		':character11:'=>"<img src='images/s/character0028.gif'>",
		':confused1:'=>"<img src='images/s/confused0001.gif'>",
		':confused2:'=>"<img src='images/s/confused0003.gif'>",
		':confused3:'=>"<img src='images/s/confused0004.gif'>",
		':confused4:'=>"<img src='images/s/confused0006.gif'>",
		':confused5:'=>"<img src='images/s/confused0007.gif'>",
		':confused6:'=>"<img src='images/s/confused0010.gif'>",
		':confused7:'=>"<img src='images/s/confused0018.gif'>",
		':confused8:'=>"<img src='images/s/confused0019.gif'>",
		':confused9:'=>"<img src='images/s/confused0020.gif'>",
		':confused10:'=>"<img src='images/s/confused0024.gif'>",
		':confused11:'=>"<img src='images/s/confused0033.gif'>",
		':confused14:'=>"<img src='images/s/confused0066.gif'>",
		':confused15:'=>"<img src='images/s/confused0068.gif'>",
		':cool1:'=>"<img src='images/s/cool0001.gif'>",
		':cool2:'=>"<img src='images/s/cool0006.gif'>",
		':cool3:'=>"<img src='images/s/cool0008.gif'>",
		':cool4:'=>"<img src='images/s/cool0010.gif'>",
		':cool5:'=>"<img src='images/s/cool0013.gif'>",
		':cool6:'=>"<img src='images/s/cool0017.gif'>",
		':cool7:'=>"<img src='images/s/cool0020.gif'>",
		':cool8:'=>"<img src='images/s/cool0025.gif'>",
		':cool9:'=>"<img src='images/s/cool0036.gif'>",
		':cool10:'=>"<img src='images/s/cool0044.gif'>",
		':cool11:'=>"<img src='images/s/cool0046.gif'>",
		':fight1:'=>"<img src='images/s/fighting0012.gif'>",
		':fight2:'=>"<img src='images/s/fighting0018.gif'>",
		':fight3:'=>"<img src='images/s/fighting0019.gif'>",
		':fight4:'=>"<img src='images/s/fighting0023.gif'>",
		':fight6:'=>"<img src='images/s/fighting0025.gif'>",
		':fight7:'=>"<img src='images/s/fighting0026.gif'>",
		':fight8:'=>"<img src='images/s/fighting0028.gif'>",
		':fight10:'=>"<img src='images/s/fighting0030.gif'>",
		':fight11:'=>"<img src='images/s/fighting0031.gif'>",
		':fight12:'=>"<img src='images/s/fighting0032.gif'>",
		':fight13:'=>"<img src='images/s/fighting0036.gif'>",
		':fight14:'=>"<img src='images/s/fighting0039.gif'>",
		':fight15:'=>"<img src='images/s/fighting0040.gif'>",
		':fight16:'=>"<img src='images/s/fighting0041.gif'>",
		':fight17:'=>"<img src='images/s/fighting0044.gif'>",
		':fight18:'=>"<img src='images/s/fighting0045.gif'>",
		':fight19:'=>"<img src='images/s/fighting0047.gif'>",
		':fight20:'=>"<img src='images/s/fighting0048.gif'>",
		':fight22:'=>"<img src='images/s/fighting0054.gif'>",
		':fight23:'=>"<img src='images/s/fighting0056.gif'>",
		':fight24:'=>"<img src='images/s/fighting0057.gif'>",
		':fight25:'=>"<img src='images/s/fighting0058.gif'>",
		':fight26:'=>"<img src='images/s/fighting0059.gif'>",
		':fight27:'=>"<img src='images/s/fighting0064.gif'>",
		':fight28:'=>"<img src='images/s/fighting0069.gif'>",
		':fight29:'=>"<img src='images/s/fighting0070.gif'>",
		':fight30:'=>"<img src='images/s/fighting0071.gif'>",
		':fight31:'=>"<img src='images/s/fighting0074.gif'>",
		':fight32:'=>"<img src='images/s/fighting0077.gif'>",
		':fight33:'=>"<img src='images/s/fighting0078.gif'>",
		':fight34:'=>"<img src='images/s/fighting0079.gif'>",
		':fight35:'=>"<img src='images/s/fighting0081.gif'>",
		':fight36:'=>"<img src='images/s/fighting0082.gif'>",
		':fight37:'=>"<img src='images/s/fighting0083.gif'>",
		':fight38:'=>"<img src='images/s/fighting0086.gif'>",
		':fight39:'=>"<img src='images/s/fighting0087.gif'>",
		':fight40:'=>"<img src='images/s/fighting0088.gif'>",
		':fight41:'=>"<img src='images/s/fighting0092.gif'>",
		':fight43:'=>"<img src='images/s/fighting0097.gif'>",
		':happy1:'=>"<img src='images/s/happy0001.gif'>",
		':happy2:'=>"<img src='images/s/happy0003.gif'>",
		':happy3:'=>"<img src='images/s/happy0004.gif'>",
		':happy5:'=>"<img src='images/s/happy0007.gif'>",
		':happy6:'=>"<img src='images/s/happy0011.gif'>",
		':happy7:'=>"<img src='images/s/happy0013.gif'>",
		':happy8:'=>"<img src='images/s/happy0017.gif'>",
		':happy9:'=>"<img src='images/s/happy0019.gif'>",
		':happy10:'=>"<img src='images/s/happy0020.gif'>",
		':happy11:'=>"<img src='images/s/happy0025.gif'>",
		':happy12:'=>"<img src='images/s/happy0034.gif'>",
		':happy13:'=>"<img src='images/s/happy0045.gif'>",
		':happy14:'=>"<img src='images/s/happy0064.gif'>",
		':happy15:'=>"<img src='images/s/happy0065.gif'>",
		':happy16:'=>"<img src='images/s/happy0069.gif'>",
		':happy17:'=>"<img src='images/s/happy0071.gif'>",
		':happy18:'=>"<img src='images/s/happy0080.gif'>",
		':happy19:'=>"<img src='images/s/happy0099.gif'>",
		':happy20:'=>"<img src='images/s/happy0100.gif'>",
		':indif1:'=>"<img src='images/s/indifferent0008.gif'>",
		':indif2:'=>"<img src='images/s/indifferent0016.gif'>",
		':indif3:'=>"<img src='images/s/indifferent0018.gif'>",
		':indif4:'=>"<img src='images/s/indifferent0020.gif'>",
		':indif5:'=>"<img src='images/s/indifferent0021.gif'>",
		':innocent1:'=>"<img src='images/s/innocent0001.gif'>",
		':innocent2:'=>"<img src='images/s/innocent0002.gif'>",
		':innocent3:'=>"<img src='images/s/innocent0003.gif'>",
		':innocent4:'=>"<img src='images/s/innocent0006.gif'>",
		':innocent5:'=>"<img src='images/s/innocent0007.gif'>",
		':innocent6:'=>"<img src='images/s/innocent0008.gif'>",
		':jumping1:'=>"<img src='images/s/jumping0001.gif'>",
		':jumping2:'=>"<img src='images/s/jumping0002.gif'>",
		':jumping3:'=>"<img src='images/s/jumping0003.gif'>",
		':jumping4:'=>"<img src='images/s/jumping0005.gif'>",
		':jumping5:'=>"<img src='images/s/jumping0014.gif'>",
		':jumping6:'=>"<img src='images/s/jumping0017.gif'>",
		':jumping7:'=>"<img src='images/s/jumping0018.gif'>",
		':love1:'=>"<img src='images/s/love0001.gif'>",
		':love2:'=>"<img src='images/s/love0004.gif'>",
		':love3:'=>"<img src='images/s/love0006.gif'>",
		':love4:'=>"<img src='images/s/love0016.gif'>",
		':love5:'=>"<img src='images/s/love0021.gif'>",
		':love6:'=>"<img src='images/s/love0028.gif'>",
		':love7:'=>"<img src='images/s/love0030.gif'>",
		':love8:'=>"<img src='images/s/love0031.gif'>",
		':love9:'=>"<img src='images/s/love0036.gif'>",
		':love10:'=>"<img src='images/s/love0037.gif'>",
		':love11:'=>"<img src='images/s/love0038.gif'>",
		':love12:'=>"<img src='images/s/love0044.gif'>",
		':love13:'=>"<img src='images/s/love0045.gif'>",
		':love14:'=>"<img src='images/s/love0052.gif'>",
		':love15:'=>"<img src='images/s/love0055.gif'>",
		':love16:'=>"<img src='images/s/love0056.gif'>",
		':love17:'=>"<img src='images/s/love0057.gif'>",
		':love18:'=>"<img src='images/s/love0065.gif'>",
		':love19:'=>"<img src='images/s/love0066.gif'>",
		':love20:'=>"<img src='images/s/love0067.gif'>",
		':love21:'=>"<img src='images/s/love0068.gif'>",
		':love22:'=>"<img src='images/s/love0070.gif'>",
		':love23:'=>"<img src='images/s/love0071.gif'>",
		':love24:'=>"<img src='images/s/love0072.gif'>",
		':mad1:'=>"<img src='images/s/mad0009.gif'>",
		':mad2:'=>"<img src='images/s/mad0012.gif'>",
		':mad3:'=>"<img src='images/s/mad0013.gif'>",
		':mad4:'=>"<img src='images/s/mad0015.gif'>",
		':mad5:'=>"<img src='images/s/mad0025.gif'>",
		':mad6:'=>"<img src='images/s/mad0034.gif'>",
		':mad7:'=>"<img src='images/s/mad0040.gif'>",
		':mad8:'=>"<img src='images/s/mad0041.gif'>",
		':mad9:'=>"<img src='images/s/mad0055.gif'>",
		':mad11:'=>"<img src='images/s/mad0066.gif'>",
		':mad12:'=>"<img src='images/s/mad0068.gif'>",
		':party1:'=>"<img src='images/s/party0001.gif'>",
		':party2:'=>"<img src='images/s/party0002.gif'>",
		':party3:'=>"<img src='images/s/party0003.gif'>",
		':party4:'=>"<img src='images/s/party0005.gif'>",
		':party5:'=>"<img src='images/s/party0007.gif'>",
		':party6:'=>"<img src='images/s/party0009.gif'>",
		':party7:'=>"<img src='images/s/party0011.gif'>",
		':party8:'=>"<img src='images/s/party0012.gif'>",
		':party9:'=>"<img src='images/s/party0016.gif'>",
		':party10:'=>"<img src='images/s/party0017.gif'>",
		':party11:'=>"<img src='images/s/party0018.gif'>",
		':party12:'=>"<img src='images/s/party0020.gif'>",
		':party13:'=>"<img src='images/s/party0021.gif'>",
		':party14:'=>"<img src='images/s/party0023.gif'>",
		':party15:'=>"<img src='images/s/party0024.gif'>",
		':party16:'=>"<img src='images/s/party0029.gif'>",
		':party17:'=>"<img src='images/s/party0031.gif'>",
		':party15:'=>"<img src='images/s/party0036.gif'>",
		':party16:'=>"<img src='images/s/party0037.gif'>",
		':party17:'=>"<img src='images/s/party0038.gif'>",
		':party18:'=>"<img src='images/s/party0039.gif'>",
		':party19:'=>"<img src='images/s/party0046.gif'>",
		':party20:'=>"<img src='images/s/party0048.gif'>",
		':party21:'=>"<img src='images/s/party0051.gif'>",
		':party22:'=>"<img src='images/s/party0052.gif'>",
		':rolleye1:'=>"<img src='images/s/rolleye0007.gif'>",
		':rolleye2:'=>"<img src='images/s/rolleye0009.gif'>",
		':rolleye3:'=>"<img src='images/s/rolleye0011.gif'>",
		':rolleye4:'=>"<img src='images/s/rolleye0012.gif'>",
		':rolleye5:'=>"<img src='images/s/rolleye0018.gif'>",
		':sad1:'=>"<img src='images/s/sad0002.gif'>",
		':sad2:'=>"<img src='images/s/sad0006.gif'>",
		':sad3:'=>"<img src='images/s/sad0012.gif'>",
		':sad4:'=>"<img src='images/s/sad0013.gif'>",
		':sad5:'=>"<img src='images/s/sad0016.gif'>",
		':sad6:'=>"<img src='images/s/sad0017.gif'>",
		':sad7:'=>"<img src='images/s/sad0024.gif'>",
		':sad8:'=>"<img src='images/s/sad0028.gif'>",
		':sad9:'=>"<img src='images/s/sad0049.gif'>",
		':sad10:'=>"<img src='images/s/sad0061.gif'>",
		':sad11:'=>"<img src='images/s/sad0064.gif'>",
		':sad12:'=>"<img src='images/s/sad0070.gif'>",
		':sad13:'=>"<img src='images/s/sad0086.gif'>",
		':scared1:'=>"<img src='images/s/scared0001.gif'>",
		':scared2:'=>"<img src='images/s/scared0008.gif'>",
		':scared3:'=>"<img src='images/s/scared0010.gif'>",
		':scared4:'=>"<img src='images/s/scared0012.gif'>",
		':scared5:'=>"<img src='images/s/scared0013.gif'>",
		':scared6:'=>"<img src='images/s/scared0014.gif'>",
		':scared7:'=>"<img src='images/s/scared0015.gif'>",
		':scared8:'=>"<img src='images/s/scared0016.gif'>",
		':scared9:'=>"<img src='images/s/scared0017.gif'>",
		':scared10:'=>"<img src='images/s/scared0018.gif'>",
		':sick1:'=>"<img src='images/s/sick0004.gif'>",
		':sick2:'=>"<img src='images/s/sick0007.gif'>",
		':sick3:'=>"<img src='images/s/sick0009.gif'>",
		':sick4:'=>"<img src='images/s/sick0010.gif'>",
		':sick5:'=>"<img src='images/s/sick0012.gif'>",
		':sick6:'=>"<img src='images/s/sick0013.gif'>",
		':sick7:'=>"<img src='images/s/sick0018.gif'>",
		':sick8:'=>"<img src='images/s/sick0019.gif'>",
		':sick9:'=>"<img src='images/s/sick0020.gif'>",
		':sick10:'=>"<img src='images/s/sick0024.gif'>",
		':sick11:'=>"<img src='images/s/sick0025.gif'>",
		':sick12:'=>"<img src='images/s/sick0026.gif'>",




		':iamstupid:'=>"<img src='images/s/iamstupid.gif'>",
		':lame:'=>"<img src='images/s/lame.gif'>",
		':jerry:'=>"<img src='images/s/jerry.gif'>",
		':kewlpics:'=>"<img src='images/s/kewlpics.gif'>",
		':offtopic:'=>"<img src='images/s/offtopic.gif'>",
		':oops:'=>"<img src='images/s/oops.gif'>",
		':sign0010:'=>"<img src='images/s/sign0010.gif'>",
		':spam:'=>"<img src='images/s/spam.gif'>",
		':warisnotanswer:'=>"<img src='images/s/warisnotanswer.gif'>",
		':welcome1:'=>"<img src='images/s/welcome1.gif'>",
		':iamwithstupid:'=>"<img src='images/s/iamwithstupid.gif'>",
		':offtopic:'=>"<img src='images/s/offtopic.gif'>",
		':rockonbaby:'=>"<img src='images/s/sign0021.gif'>",


		':yesmaster:'=>"<img src='images/s/yesmaster.gif'>",
		':goodjob1:'=>"<img src='images/s/goodjob1.gif'>",
		':anyone:'=>"<img src='images/s/anyone.gif'>",
		':noway:'=>"<img src='images/s/noway.gif'>",

		':hellno:'=>"<img src='images/s/hellno.gif'>",

		':noway1:'=>"<img src='images/s/noway1.gif'>",

		':goodone:'=>"<img src='images/s/goodone.gif'>",
		':iamnewhere:'=>"<img src='images/s/iamnewhere.gif'>",
		':youwish:'=>"<img src='images/s/youwish.gif'>",
		':awwwman:'=>"<img src='images/s/awwwman.gif'>",
		':missyou:'=>"<img src='images/s/missyou.gif'>",
		':muahaha:'=>"<img src='images/s/muahaha.gif'>",


		':tongue0001: '=>"<img src='images/s/tongue0001.gif'>",
		':tongue0002: '=>"<img src='images/s/tongue0002.gif'>",
		':tongue0003: '=>"<img src='images/s/tongue0003.gif'>",
		':tongue0007: '=>"<img src='images/s/tongue0007.gif'>",
		':tongue0011: '=>"<img src='images/s/tongue0011.gif'>",
		':tongue0013: '=>"<img src='images/s/tongue0013.gif'>",
		':tongue0014: '=>"<img src='images/s/tongue0014.gif'>",
		':tongue0020: '=>"<img src='images/s/tongue0020.gif'>",
		':tongue0021: '=>"<img src='images/s/tongue0021.gif'>",
		':tongue0022: '=>"<img src='images/s/tongue0022.gif'>",
		':winking0001: '=>"<img src='images/s/winking0001.gif'>",
		':winking0008: '=>"<img src='images/s/winking0008.gif'>",
		':winking0017: '=>"<img src='images/s/winking0017.gif'>",
		':winking0026: '=>"<img src='images/s/winking0026.gif'>",
		':winking0047: '=>"<img src='images/s/winking0047.gif'>",

	);
	if($para == 'normal') {
		$text = str_replace(array_keys($codes),array_values($codes),$text);
		return $text;
	}
	else {
		return $codes;
	}
	

}

?>