<?php

namespace App\Entity;

use App\Repository\SensorsUplinksRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SensorsUplinksRepository::class)
 */
class SensorsUplinks
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Sensors::class, inversedBy="uplinks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sensor;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $battery;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $waterFlowRate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fromSensorDate;

    public function __toString()
    {
        return $this->getDate()->format('d/m/Y H:i');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSensor(): ?Sensors
    {
        return $this->sensor;
    }

    public function setSensor(?Sensors $sensor): self
    {
        $this->sensor = $sensor;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getBattery(): ?float
    {
        return $this->battery;
    }

    public function setBattery(?float $battery): self
    {
        $this->battery = $battery;

        return $this;
    }

    public function getWaterFlowRate(): ?float
    {
        return $this->waterFlowRate;
    }

    public function setWaterFlowRate(?float $waterFlowRate): self
    {
        $this->waterFlowRate = $waterFlowRate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return \DateTime
     */
    public function getFromSensorDate()
    {
        return $this->fromSensorDate;
    }

    /**
     * @param $fPort
     * @param $bytes
     * @return array
     */
    public function decodePayload($fPort, $bytes) {
        $log = [];
        $hex = str_split($bytes, 2);
        $now = new \DateTime();
        $this->getSensor()->setLastSeen($now);
        switch($fPort){
            case 2:
                $this->type = "Water Flow Value";

                $flag = 2;

                $pulse = hexdec($hex[1].$hex[2].$hex[3].$hex[4]);

                if($this->getSensor()->getLastFlowTime() instanceof \DateTime) {
                    $diff = $this->getSensor()->getLastFlowTime()->diff($now);
                    $interval = ($diff->h * 60)+$diff->i;
                } else {
                    $interval = 20;
                }
                $this->getSensor()->setLastFlowTime($now);

                if($hex[5] == 1){
                    $log['fromLastUplinkPulse'] = $pulse;
                    $this->type .= " From Last Uplink";
                    $this->getSensor()->setLastFlowPulse($this->getSensor()->getLastFlowPulse()+$pulse);
                    $log['Mode'] = 1;
                } else {
                    $log['fromStartPulse'] = $pulse;
                    $this->type .= " From Start";
                    $pulse -= $this->getSensor()->getLastFlowPulse();
                    $this->getSensor()->setLastFlowPulse($pulse);
                    $log['Mode'] = 0;
                }

                if($flag == 2) {
                    $this->setWaterFlowRate(($pulse/60) / $interval);
                } elseif($flag == 1) {
                    $this->setWaterFlowRate(($pulse/360)/ $interval);
                } else {
                    $this->setWaterFlowRate(($pulse/450) / $interval);
                }

                $date = new \DateTime();
                $date->setTimestamp(hexdec($hex[7].$hex[8].$hex[9].$hex[10]));
                $this->fromSensorDate = $date;
                break;
            case 3:
                $this->type = "Historical Water Flow";
                break;
            case 4:
                $this->type = "Sensor Configuration";
                break;
            case 5:
                $this->type = "Device Status";
                if($hex[0] == 11){
                    $log['Sensor_Type'] = 'Dragino SW3L';
                }
                $subVersion = str_split($hex[2], 1);
                $log['Firmware_Version'] = 'v'.$hex[1].'.'.$subVersion[0].'.'.$subVersion[1];

                switch($hex[3]){
                    case '01':
                        $log['Frequency_Band'] = "EU868";
                        break;
                    case '02':
                        $log['Frequency_Band'] = "US915";
                        break;
                    case '03':
                        $log['Frequency_Band'] = "IN865";
                        break;
                    case '04':
                        $log['Frequency_Band'] = "AU915";
                        break;
                    case '05':
                        $log['Frequency_Band'] = "KZ865";
                        break;
                    case '06':
                        $log['Frequency_Band'] = "RU864";
                        break;
                    case '07':
                        $log['Frequency_Band'] = "AS923";
                        break;
                    case '08':
                        $log['Frequency_Band'] = "AS923-1";
                    break;
                    case '09':
                        $log['Frequency_Band'] = "AS923-2";
                    break;
                    case '0A':
                        $log['Frequency_Band'] = "AS923-3";
                        break;
                    case '0B':
                        $log['Frequency_Band'] = "CN470";
                        break;
                    case '0C':
                        $log['Frequency_Band'] = "EU433";
                        break;
                    case '0D':
                        $log['Frequency_Band'] = "KR920";
                        break;
                    case '0E':
                        $log['Frequency_Band'] = "MA869";
                        break;
                }

                $batteryVoltage = hexdec($hex[5].$hex[6])/1000;
                $this->setBattery($batteryVoltage);
                $this->getSensor()->setLastBattery($batteryVoltage);
                break;
        }


        /*
                else if(fPort==0x03)
                {
                    for(var i=0;i<bytes.length;i=i+11)
            {
                var data= datalog(i,bytes);
                if(i=='0')
                    data_sum=data;
                else
                    data_sum+=data;
            }
            return{
                    DATALOG:data_sum
            };
            }
                else if(fPort==0x04)
                {
                    var tdc= bytes[0]<<16 | bytes[1]<<8 | bytes[2];
                    var stop_timer= bytes[4];
                    var alarm_timer= bytes[5]<<8 | bytes[6];

                    return {
                    TDC:tdc,
            Stop_Timer:stop_timer,
            Alarm_Timer:alarm_timer,
            };
            }
            }*/
        return $log;
    }
}
