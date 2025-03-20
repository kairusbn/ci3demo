<?php

/**
 * @OA\Schema(
 *     title="Patient model",
 *     description="Patient model",
 * )
 */
class Patient
{
    /**
     * @OA\Property(description="Patient Health Record Number",title="Patient Health Record Number")
     * @var string
     */
    private $hpercode;

    /**
     * @OA\Property(description="First Name",title="First Name")
     * @var string
     */
    private $patfirst;

    /**
     * @OA\Property(description="Last Name", title="Last Name")
     * @var string
     */
    private $patlast;

    /**
     * @OA\Property(description="Middle Name",title="Middle Name")
     * @var string
     */
    private $patmiddle;
}