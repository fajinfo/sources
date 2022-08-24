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

    public function decodePayload($fPort, $bytes) {
        $log = [];
        switch($fPort){
            case 2:
                $this->type = "Water Flow Value";
                $hex = str_split($bytes, 2);
                break;
            case 3:
                $this->type = "Historical Water Flow";
                break;
            case 4:
                $this->type = "Sensor Configuration";
                break;
            case 5:
                $this->type = "Device Status";
                break;
        }


        /*if($fPort==0x02)
        {
            $flag=(bytes[0]&0xFC)>>2;
            $decode = [];

            decode.MOD=bytes[5];
            decode.Calculate_flag=flag;
            decode.Alarm=(bytes[0]&0x02)?"TRUE":"FALSE";

            if(flag==2)
                decode.Water_flow_value=parseFloat((((bytes[1]<<24 | bytes[2]<<16 | bytes[3]<<8 | bytes[4])>>>0)/60).toFixed(1));
            else if(flag==1)
                decode.Water_flow_value=parseFloat((((bytes[1]<<24 | bytes[2]<<16 | bytes[3]<<8 | bytes[4])>>>0)/360).toFixed(1));
            else
                decode.Water_flow_value=parseFloat((((bytes[1]<<24 | bytes[2]<<16 | bytes[3]<<8 | bytes[4])>>>0)/450).toFixed(1));

            if(bytes[5]==0x01)
                decode.Last_pulse=((bytes[1]<<24 | bytes[2]<<16 | bytes[3]<<8 | bytes[4])>>>0);
            else
                decode.Total_pulse=((bytes[1]<<24 | bytes[2]<<16 | bytes[3]<<8 | bytes[4])>>>0);

            decode.Data_time= getMyDate((bytes[7]<<24 | bytes[8]<<16 | bytes[9]<<8 | bytes[10]).toString(10));

            if(bytes.length==11)
            {
                return decode;
            }
            }
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
                else if(fPort==0x05)
                {
                    var sub_band;
                    var freq_band;
                    var sensor;

                    if(bytes[0]==0x11)
                        sensor= "SW3L";

                    if(bytes[4]==0xff)
                        sub_band="NULL";
                    else
                        sub_band=bytes[4];

                    if(bytes[3]==0x01)
                        freq_band="EU868";
                    else if(bytes[3]==0x02)
                        freq_band="US915";
                    else if(bytes[3]==0x03)
                        freq_band="IN865";
                    else if(bytes[3]==0x04)
                        freq_band="AU915";
                    else if(bytes[3]==0x05)
                        freq_band="KZ865";
                    else if(bytes[3]==0x06)
                        freq_band="RU864";
                    else if(bytes[3]==0x07)
                        freq_band="AS923";
                    else if(bytes[3]==0x08)
                        freq_band="AS923_1";
                    else if(bytes[3]==0x09)
                        freq_band="AS923_2";
                    else if(bytes[3]==0x0A)
                        freq_band="AS923_3";
                    else if(bytes[3]==0x0B)
                        freq_band="CN470";
                    else if(bytes[3]==0x0C)
                        freq_band="EU433";
                    else if(bytes[3]==0x0D)
                        freq_band="KR920";
                    else if(bytes[3]==0x0E)
                        freq_band="MA869";

                    var firm_ver= (bytes[1]&0x0f)+'.'+(bytes[2]>>4&0x0f)+'.'+(bytes[2]&0x0f);
                    var bat= (bytes[5]<<8 | bytes[6])/1000;

                    return {
                    SENSOR_MODEL:sensor,
            FIRMWARE_VERSION:firm_ver,
            FREQUENCY_BAND:freq_band,
            SUB_BAND:sub_band,
            BAT:bat,
            };
            }*/
    }
}
