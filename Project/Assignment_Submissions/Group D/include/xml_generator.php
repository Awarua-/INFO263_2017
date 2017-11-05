<?php
function generate_xml($vehicles)
{
    if (file_exists('vehicle_markers.xml'))
    {
        unlink('vehicle_markers.xml');
    }

$writer = new XMLWriter();
$writer->openURI('vehicle_markers.xml');
$writer->startDocument('1.0','UTF-8');
$writer->setIndent(true);
$writer->startElement('markers');
foreach ($vehicles as $vehicle)
{
    $writer->startElement('marker');
    $writer->writeAttribute('id', $vehicle->vehicle_id);
    $writer->writeAttribute('lat', $vehicle->latitude);
    $writer->writeAttribute('lng', $vehicle->longitude);
    $writer->writeAttribute('type', 'vehicle');
    $writer->writeAttribute('start_time', $vehicle->start_time);
    $writer->writeAttribute('timestamp', $vehicle->timestamp);
    $writer->endElement();
};
$writer->endElement();
$writer->endDocument();
$writer->flush();
}
?>