<?php
if ($_GET['tu_khoa'] != "") {
    $phamang = explode(" ", trim($_GET['tu_khoa']));
    $phamang = xoa_phan_tu_mang_rong($phamang);
//    echo json_encode($phamang);die;
    $ntc = "";
    for ($fff = 0; $fff < count($phamang) - 1; $fff++) {
        $ntc = $ntc . "ten like '%$phamang[$fff]%' or ";
    }
    $sooo = count($phamang) - 1;
    $ntc = $ntc . "ten like '%$phamang[$sooo]%'";
    $chuoi_or = $ntc;
    $chuoi_or = " ( $chuoi_or ) and ";
} else {
    $chuoi_or = "";
}

function xuat_link($st) {
    //if($_GET['trang']==""){$_GET['trang']=1;}
    ?>
    <style>
        a.pt3
        {
            color:black;
            text-decoration: none;
            font-weight:bold;
        }
        a.pt3:hover
        {
            color:red;
            text-decoration: none;
            font-weight:bold;
        }
    </style>
    <?php
    echo "<center>";
    if ($_GET['trang'] != "") {
        if (ereg("&trang=", $_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = str_replace("&trang=", "", $_SERVER['REQUEST_URI']);
            $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 0, -strlen($_GET['trang']));
            $lpt = $_SERVER['REQUEST_URI'] . "&trang=";
        } else {
            $lpt = $_SERVER['REQUEST_URI'] . "&trang=";
        }
    } else {
        $_SERVER['REQUEST_URI'] = str_replace("&trang=", "", $_SERVER['REQUEST_URI']);
        $lpt = $_SERVER['REQUEST_URI'] . "&trang=";
    }
    if ($_GET['trang'] != "" and $_GET['trang'] != "1") {
        if ($_GET['trang'] == "" or $_GET['trang'] == 1) {
            $k = 1;
        } else {
            $k = $_GET['trang'] - 1;
        }
        $link_t = $lpt . $k;
        $link_d = $lpt . "1";
        echo '<a href="' . $link_d . '" style="margin-right:10px" class="pt3">Đầu</a>';
        echo '<a href="' . $link_t . '" style="margin-right:10px" class="pt3">Trước</a>';
    }
    if ($_GET['trang'] == "") {
        $a = 1;
    } else {
        $a = $_GET['trang'];
    }
    $b_1 = $_GET['trang'] - 5;
    $n_1 = $b_1;
    if ($b_1 < 1) {
        $b_1 = 1;
    }
    $b_2 = $_GET['trang'] + 5;
    if ($b_2 >= $st) {
        $n_2 = $b_2;
        $b_2 = $st;
    }
    //echo $b_1."<hr>";
    if ($n_1 < 0) {
        $v = (-1) * $n_1;
        $b_2 = $b_2 + $v;
    }
    if ($n_2 >= $st) {
        $v_2 = $n_2 - $st;
        $b_1 = $b_1 - $v_2;
    }
    if ($b_1 > 1) {
        echo ' ... ';
    }
    for ($i = $b_1; $i <= $b_2; $i++) {
        $lpt_1 = $lpt . $i;
        if ($i > 0 && $i <= $st) {
            if ($i != $a) {
                ?>
                <a href="<?php echo $lpt_1; ?>" class="pt3"><?php echo $i; ?> </a>
                <?php
            } else {
                echo "<b style=\"color:red\">$i</b>";
            }
        }
    }
    if ($b_2 < $st) {
        echo ' ... ';
    }
    if ($_GET['trang'] != $st && $st != 1) {
        if ($_GET['trang'] == $st) {
            $k = $st;
        } else {
            $k = $_GET['trang'] + 1;
            if ($_GET['trang'] == "") {
                $k = 2;
            }
        }
        $link_s = $lpt . $k;
        $link_cuoi = $lpt . $st;
        echo '<a href="' . $link_s . '" style="margin-left:10px" class="pt3">Sau</a>';
        echo '<a href="' . $link_cuoi . '" style="margin-left:10px" class="pt3">Cuối</a>';
    }
    echo "</center>";
}

function kiem_tra_menu_con($id) {
    $tv = "select count(*) from menu where thuoc_menu='$id'";
    $tv_1 = mysql_query($tv);
    $tv_2 = mysql_fetch_row($tv_1);
    if ($tv_2[0] != 0) {
        return "co";
    } else {
        return "khong";
    }
}

function hop_option_de_quy($id, $so) {
    $so++;
    $kt = "";
    for ($i = 1; $i <= $so; $i++) {
        $kt = $kt . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
    }
    $tv = "select * from menu where vitri_menu='doc' and thuoc_menu='$id' order by id";
    $tv_1 = mysql_query($tv);
    while ($tv_2 = mysql_fetch_array($tv_1)) {
        if ($_GET['id'] == $tv_2['id']) {
            $sl = "selected";
        } else {
            $sl = "";
        }
        echo "<option value=\"$tv_2[id]\" $sl>";
        echo $kt;
        echo $tv_2['ten'];
        echo "</option>";
        $ktmnc = kiem_tra_menu_con($tv_2['id']);
        if ($ktmnc == "co") {
            hop_option_de_quy($tv_2['id'], $so);
        }
    }
}

function hop_option() {
    $tv = "select * from menu where vitri_menu='doc' and thuoc_menu='' order by id";
    $tv_1 = mysql_query($tv);
    echo "<select name=\"cap_do\" onchange=\"chuyen_avc(this.value)\">";
    echo "<option value=\"\">Tất cả</option>";
    while ($tv_2 = mysql_fetch_array($tv_1)) {
        if ($_GET['id'] == $tv_2['id']) {
            $sl = "selected";
        } else {
            $sl = "";
        }
        echo "<option value=\"$tv_2[id]\" $sl>";
        echo $tv_2['ten'];
        echo "</option>";
        $ktmnc = kiem_tra_menu_con($tv_2['id']);
        if ($ktmnc == "co") {
            hop_option_de_quy($tv_2['id'], 0);
        }
    }
    echo "</select>";
}

function chuoi_id_menu_con($id_cha, $chuoi) {
    $tv = "select * from menu where thuoc_menu='$id_cha' order by id";
    $tv_1 = mysql_query($tv);
    while ($tv_2 = mysql_fetch_array($tv_1)) {
        $chuoi = $chuoi . $tv_2['id'] . ",";
        $r = "select count(*) from menu where thuoc_menu='$id_cha'";
        $r_1 = mysql_query($r);
        $r_2 = mysql_fetch_row($r_1);
        if ($r_2[0] != 0) {
            $chuoi = chuoi_id_menu_con($tv_2['id'], $chuoi);
        }
    }
    return $chuoi;
}

function mang_id_menu_con($id_menu) {
    if ($id_menu != "") {
        $chuoi = $id_menu . ",";
    } else {
        $chuoi = "";
    }
    $chuoi_id_menu_con = chuoi_id_menu_con($id_menu, $chuoi);
    $mang = explode(",", $chuoi_id_menu_con);
    unset($mang[count($mang) - 1]);
    return $mang;
}

function dem_bcd($id, $chuoi_or) {
    $tv = "select count(*) from san_pham where $chuoi_or thuoc_menu='$id'";
    $tv_1 = mysql_query($tv);
    $tv_2 = mysql_fetch_row($tv_1);
    return $tv_2[0];
}
?>

<li class="navigation-item">
    <form action="" method="get" class="search-form ">
        <input type="hidden" name="thamso" value="tim_kiem">

        <?php
        if ($_GET['tu_khoa'] != "") {
            $tu_khoa = $_GET['tu_khoa'];
        }
        ?>
        <div class="search-group">
            <span class="search-input-group">
                <input name="tu_khoa" class="input-text" placeholder="Từ khóa tìm kiếm" name="tu_khoa" value="<?php echo $tu_khoa; ?>"/>
                <label class="search-button-wrap">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    <input type="submit" value="submit" style="display : none" />
                </label>			
            </span>
            <!--        <div class="input-group-btn">
                        <input type="hidden" id="search-param" name="post_type" value="product">
                    </div>-->
        </div>

    </form>			

</li>
<li class="navigation-item language-link-wrapper">
    <a class="language-link" href="index.php?lang=en"><img src="image/Giaodien/en.png"  /></a>
    <a class="language-link" href="index.php?lang=vn"><img src="image/Giaodien/vi.png"  /></a>
</li>





