<?php

namespace App\Controller;

use App\Repository\MembreLicencesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Request;
use App\Statistiques\StatistiquesSorties;

// Include PhpSpreadsheet required namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

class ExportExcelController extends AbstractController
{
    private $membreLicencesRepository;

    public function __construct(MembreLicencesRepository $membreLicencesRepository)
    {
        $this->membreLicencesRepository = $membreLicencesRepository;
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportKmParcourusParMembre(Request $request, $annee, $mois)
    {
        $statistiques = new StatistiquesSorties($this->getDoctrine()->getManager()->getRepository('App:Sortie'), $annee, $mois);
        $listeStatistiques = $statistiques->getStatistiquesParMembre()->getStatistiques();
        usort($listeStatistiques, array($this, "triKmParcourusDesc"));

        return $this->DonneClasseurExcel('Membre', $listeStatistiques);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportNombreDeSortiesParMembre(Request $request, $annee, $mois)
    {
        $statistiques = new StatistiquesSorties($this->getDoctrine()->getManager()->getRepository('App:Sortie'), $annee, $mois);
        $listeStatistiques = $statistiques->getStatistiquesParMembre()->getStatistiques();
        usort($listeStatistiques, array($this, "triNombreDeSortiesDesc"));

        return $this->DonneClasseurExcel('Membre', $listeStatistiques);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportKmParcourusParBateau(Request $request, $annee, $mois)
    {
        $statistiques = new StatistiquesSorties($this->getDoctrine()->getManager()->getRepository('App:Sortie'), $annee, $mois);
        $listeStatistiques = $statistiques->getStatistiquesParBateau()->getStatistiques();
        usort($listeStatistiques, array($this, "triKmParcourusDesc"));

        return $this->DonneClasseurExcel('Bateau', $listeStatistiques);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportNombreDeSortiesParBateau(Request $request, $annee, $mois)
    {
        $statistiques = new StatistiquesSorties($this->getDoctrine()->getManager()->getRepository('App:Sortie'), $annee, $mois);
        $listeStatistiques = $statistiques->getStatistiquesParBateau()->getStatistiques();
        usort($listeStatistiques, array($this, "triNombreDeSortiesDesc"));

        return $this->DonneClasseurExcel('Bateau', $listeStatistiques);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function exportLicencesASaisir()
    {
        $licencesASaisir = $this->membreLicencesRepository->DonneLicencesASaisir();

        $spreadsheet = new Spreadsheet();
        $dateDuJour = new \DateTime();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Licences");
        $sheet->setCellValue('A1', 'CodeAdherent');
        $sheet->setCellValue('B1', 'Civilité');
        $sheet->setCellValue('C1', 'Nom');
        $sheet->setCellValue('D1', 'Prénom');
        $sheet->setCellValue('E1', 'Prénom2');
        $sheet->setCellValue('F1', 'Prénom3');
        $sheet->setCellValue('G1', 'NomNaissance');
        $sheet->setCellValue('H1', 'Nationalité');
        $sheet->setCellValue('I1', 'DateNaissance');
        $sheet->setCellValue('J1', 'PaysNaissance');
        $sheet->setCellValue('K1', 'DepartementNaissance');
        $sheet->setCellValue('L1', 'VilleNaissance');
        $sheet->setCellValue('M1', 'NomPere');
        $sheet->setCellValue('N1', 'PrenomPere');
        $sheet->setCellValue('O1', 'NomMere');
        $sheet->setCellValue('P1', 'PrenomMere');
        $sheet->setCellValue('Q1', 'Numero');
        $sheet->setCellValue('R1', 'TypeVoie');
        $sheet->setCellValue('S1', 'LibelleVoie');
        $sheet->setCellValue('T1', 'ImmBatRes');
        $sheet->setCellValue('U1', 'AptEtageEsc');
        $sheet->setCellValue('V1', 'Lieudit');
        $sheet->setCellValue('W1', 'Cp');
        $sheet->setCellValue('X1', 'Ville');
        $sheet->setCellValue('Y1', 'Pays');
        $sheet->setCellValue('Z1', 'Telephone');
        $sheet->setCellValue('AA1', 'AutreTelephone');
        $sheet->setCellValue('AB1', 'Mobile');
        $sheet->setCellValue('AC1', 'AutreMobile');
        $sheet->setCellValue('AD1', 'Email');
        $sheet->setCellValue('AE1', 'AutreEmail');
        $sheet->setCellValue('AF1', 'Fax');
        $sheet->setCellValue('AG1', 'UtilisationAdresse');
        $sheet->setCellValue('AH1', 'SituationFamille');
        $sheet->setCellValue('AI1', 'Profession');
        $sheet->setCellValue('AJ1', 'CategSocioPro');
        $sheet->setCellValue('AK1', 'DateSouscription');
        $sheet->setCellValue('AL1', 'TypeLicence');
        $sheet->setCellValue('AM1', 'CodeManifestation');
        $sheet->setCellValue('AN1', 'Assurance IA Sport plus');
        $sheet->setCellValue('AO1', 'Date du certificat médical pratique');
        $sheet->setCellValue('AP1', 'Médecin du certificat médical pratique');
        $sheet->setCellValue('AQ1', 'Numéro du certificat médical pratique');
        $sheet->setCellValue('AR1', 'Attestation Santé pratique');
        $sheet->setCellValue('AS1', 'Date du certificat médical compétition');
        $sheet->setCellValue('AT1', 'Médecin du certificat médical compétition');
        $sheet->setCellValue('AU1', 'Numéro du certificat médical compétition');
        $sheet->setCellValue('AV1', 'Attestation Santé compétition');
        $sheet->setCellValue('AW1', 'Date du certificat médical surclassement');
        $sheet->setCellValue('AX1', 'Médecin du certificat médical surclassement');
        $sheet->setCellValue('AY1', 'Numéro du certificat médical surclassement');
        $sheet->setCellValue('AZ1', 'Pratique en Sport d\'entreprise');
        $sheet->setCellValue('BA1', 'Nom de l\'entreprise');
        $sheet->setCellValue('BB1', 'Pratiquant');

        $ligne = 2;
        foreach ($licencesASaisir as $licence) {
            $sheet->setCellValueByColumnAndRow(1, $ligne, $licence->getMembre()->getLicence());
            $sheet->setCellValueByColumnAndRow(2, $ligne, $this->DonneCivilite($licence->getMembre()->getCivilite()));
            $sheet->setCellValueByColumnAndRow(3, $ligne, $licence->getMembre()->getNom());
            $sheet->setCellValueByColumnAndRow(4, $ligne, $licence->getMembre()->getPrenom());
            $sheet->setCellValueByColumnAndRow(8, $ligne, $licence->getMembre()->getNationalite()->getCode());
            $sheet->setCellValueByColumnAndRow(9, $ligne, $this->DonneDateFormatFrancais($licence->getMembre()->getDateNaissance()));
            $sheet->setCellValueByColumnAndRow(17, $ligne, $licence->getMembre()->getNumeroVoie());
            $sheet->setCellValueByColumnAndRow(18, $ligne, $licence->getMembre()->getTypeVoie());
            $sheet->setCellValueByColumnAndRow(19, $ligne, $licence->getMembre()->getLibelleVoie());
            $sheet->setCellValueByColumnAndRow(20, $ligne, $licence->getMembre()->getImmBatRes());
            $sheet->setCellValueByColumnAndRow(21, $ligne, $licence->getMembre()->getAptEtageEsc());
            $sheet->setCellValueByColumnAndRow(22, $ligne, $licence->getMembre()->getLieudit());
            $sheet->setCellValueByColumnAndRow(23, $ligne, $licence->getMembre()->getCodePostal());
            $sheet->setCellValueByColumnAndRow(24, $ligne, $licence->getMembre()->getVille());
            $sheet->setCellValueByColumnAndRow(25, $ligne, 'FR');
            $sheet->setCellValueByColumnAndRow(30, $ligne, $licence->getMembre()->getEmail());
            $sheet->setCellValueByColumnAndRow(33, $ligne, 'NON');
            $sheet->setCellValueByColumnAndRow(37, $ligne, $this->DonneDateFormatFrancais($dateDuJour));
            $sheet->setCellValueByColumnAndRow(38, $ligne, $this->DonneTypeLicence($licence->getTypeLicence()));
            $sheet->setCellValueByColumnAndRow(40, $ligne, $this->DonneOuiNon($licence->getAvecIASportPlus()));
            $sheet->setCellValueByColumnAndRow(41, $ligne, $this->DonneDateFormatFrancais($licence->getDateCertificatPratique()));
            $sheet->setCellValueByColumnAndRow(44, $ligne, $this->DonneOuiNon($licence->getAvecAttestationPratique()));
            $sheet->setCellValueByColumnAndRow(45, $ligne, $this->DonneDateFormatFrancais($licence->getDateCertificatCompetition()));
            $sheet->setCellValueByColumnAndRow(48, $ligne, $this->DonneOuiNon($licence->getAvecAttestationCompetition()));
            $sheet->setCellValueByColumnAndRow(52, $ligne, 'NON');
            $sheet->setCellValueByColumnAndRow(54, $ligne, 'OUI');

            $ligne++;
        }

        $writer = new Csv($spreadsheet);
        $writer->setDelimiter(';');
        $writer->setEnclosure('');

        $fileName = 'licences.csv';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_ATTACHMENT);
    }

    private function DonneOuiNon($oui)
    {
        if ($oui)
            return 'OUI';
        else
            return 'NON';
    }

    private function DonneTypeLicence($typeLicence)
    {
        if ($typeLicence == 1)
            return 'A';
        elseif ($typeLicence == 2)
            return 'U';
        elseif ($typeLicence == 3)
            return 'I';
        elseif ($typeLicence == 4)
            return 'D90';
        elseif ($typeLicence == 5)
            return 'D30';
        elseif ($typeLicence == 6)
            return 'D7';
        else
            return '';
    }

    private function DonneDateFormatFrancais($date)
    {
        if ($date == null)
            return '';
        else
            return $date->format('d/m/Y');
    }

    private function DonneCivilite($idCivilite)
    {
        if ($idCivilite == 1)
            return 'Madame';
        elseif ($idCivilite == 2)
            return 'Monsieur';
        else
            return '';
    }

    private function triKmParcourusDesc($a, $b)
    {
        return $a->getKmParcourus() < $b->getKmParcourus();
    }

    private function triNombreDeSortiesDesc($a, $b)
    {
        return $a->getNombreDeSorties() < $b->getNombreDeSorties();
    }

    private function DonneClasseurExcel($label, $listeStatistiques)
    {
        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle("Statistiques");
        $sheet->setCellValue('A1', $label);
        $sheet->setCellValue('B1', 'Km parcourus');
        $sheet->setCellValue('C1', 'Nombre de sorties');

        $ligne = 2;
        foreach ($listeStatistiques as $stat) {
            $sheet->setCellValueByColumnAndRow(1, $ligne, $stat->getLabel());
            $sheet->setCellValueByColumnAndRow(2, $ligne, $stat->getKmParcourus());
            $sheet->setCellValueByColumnAndRow(3, $ligne, $stat->getNombreDeSorties());
            $ligne++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Statistiques.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
    }
}
