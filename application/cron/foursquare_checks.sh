echo ""
echo "==== Executing Daily Check Script ==="
echo ""
/usr/bin/php index.php checks check_venues daily

echo ""
echo "==== Executing Live Check Script ==="
echo ""
/usr/bin/php index.php checks check_venues live

echo ""
echo "Scripts complete!"
echo ""