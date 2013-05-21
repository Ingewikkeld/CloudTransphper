define php::ini::removeBlock (
    $blockName,
    $iniFile
) {
    $left_side_regex = 'perl -pi -w -e "s/^(\[?)'
    $right_side_regex = '(\]?)(.+\n)//"'
    $cmd = "${left_side_regex}${blockName}${right_side_regex}"

    exec { "${cmd} ${iniFile}" :
        path => '/usr/bin/',
    }
}
