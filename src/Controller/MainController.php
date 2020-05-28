<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Recaptcha\RecaptchaValidator;
use Symfony\Component\Form\FormError;

class MainController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index()
    {
        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/Vente-Maison", name="houseSell")
     */
    public function houseSell()
    {
        return $this->render('house/houseSell.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/Location-Maison", name="houseRent")
     */
    public function houseRent()
    {
        return $this->render('house/houseRent.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/Location-Appartement", name="appartmentRent")
     */
    public function appartementRent()
    {
        return $this->render('appartment/appartmentRent.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
    /**
     * @Route("/Vente-Appartement", name="appartmentSell")
     */
    public function appartmentSell()
    {
        return $this->render('appartment/appartmentSell.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

}
