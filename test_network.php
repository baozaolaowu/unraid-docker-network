#!/usr/bin/php
<?php
// 最小测试脚本 - 获取网络信息

echo "=== Unraid Network Info Test ===\n\n";

// 1. 获取主机 IP
echo "【主机 IP】\n";
$host_ip = trim(shell_exec("hostname -I | awk '{print $1}'"));
echo "  $host_ip\n\n";

// 2. 获取虚拟机列表
echo "【虚拟机】\n";
$vms = shell_exec("virsh list --all 2>/dev/null");
if ($vms) {
    echo $vms;
} else {
    echo "  无法获取 VM 列表\n";
}
echo "\n";

// 3. 获取运行中 VM 的 IP (通过 ARP)
echo "【VM IP (ARP)】\n";
$arp = shell_exec("arp -a 2>/dev/null | grep -v incomplete");
echo $arp;
echo "\n";

// 4. 获取 Docker 容器
echo "【Docker 容器 (Custom 网络)】\n";
$custom = shell_exec("docker ps --format '{{.Names}}' | xargs -I {} docker inspect {} --format '{{.Name}} {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' 2>/dev/null | grep -v '172\\.' | head -20");
echo $custom ?: "  无\n";
echo "\n";

echo "【Docker 容器 (Bridge/Host)】\n";
$bridge = shell_exec("docker ps --format '{{.Names}}\t{{.Ports}}' 2>/dev/null | head -20");
echo $bridge ?: "  无\n";

echo "\n=== 测试完成 ===\n";
?>