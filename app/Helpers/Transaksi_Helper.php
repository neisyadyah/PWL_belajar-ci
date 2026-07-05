<?php

if (!function_exists('hitung_ppn')) {
   
    function hitung_ppn($total_harga)
    {
        return 0.12 * $total_harga; 
    }
}

if (!function_exists('hitung_biaya_admin')) {
   
    function hitung_biaya_admin($total_harga)
    {
        if ($total_harga <= 15000000) {
            $tarif = 0.005; // 0.5%
        } elseif ($total_harga > 15000000 && $total_harga <= 35000000) {
            $tarif = 0.007; // 0.7%
        } else {
            $tarif = 0.009; // 0.9%
        }

        return $tarif * $total_harga;
    }

    function hitung_diskon_kupon($total_harga, $kupon_code)
{
    $kupon_code = strtoupper(trim($kupon_code));
    
    if ($kupon_code === 'HEMAT20') {
        return $total_harga * 0.20;
    } elseif ($kupon_code === 'HEMAT30') {
        return $total_harga * 0.30;
    } elseif ($kupon_code === 'MEMBER25') {
        return $total_harga * 0.25;
    }
    
    return 0; 
}
}