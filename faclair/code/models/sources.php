<?php

namespace models;

class sources {

  public static function getShortRef($id) {
    switch ($id) {
    	case "1":
    		return 'Eaglais &amp; Bìoball';
    		break;
      case "2":
      	return 'Rianachd Phoblach';
      	break;
      case "3":
      	return 'Foghlam Sgoile';
      	break;
      case "4":
      	return 'Glèidteachas nàdair';
      	break;
    	case "22":
    		return 'Dwelly';
    		break;
    	case "23":
    		return 'LXCP';
    		break;
    	default:
    		return '[unknown]';
    }
	}

  public static function getRef($id) {
    switch ($id) {
    	case "1":
    		return 'Briathran eaglaiseil (Eaglais na h-Alba, <em>Am Bìoball</em>)';
    		break;
      case "2":
      	return 'Briathran airson rianachd poblaich (<em>Faclair na Pàrlamaid</em>, <em>Faclair Rianachd Phoblaich</em>)';
      	break;
      case "3":
      	return 'Briathran airson sgoiltean (Stòrlann, Foghlam Alba)';
      	break;
      case "4":
      	return 'Briathran à Buidhinn Nàdair na h-Alba';
      	break;
    	case "22":
    		return 'Briathran à Dwelly – <em>Faclair Gàidhlig gu Beurla le Dealbhan</em>';
    		break;
    	case "23":
    		return 'Briathran à stòr <em>Lexicopia</em>';
    		break;
    	default:
    		return '[unknown]';
    }
	}

  public static function getEmoji($id) {
    switch ($id) {
      case "1":
        return '⛪️';
        break;
      case "2":
        return '🗳';
        break;
      case "3":
        return '🎒';
        break;
      case "4":
        return '🌿';
        break;
      case "22":
        return '🗝';
        break;
      case "23":
        return '♒️';
        break;
      default:
        return '';
    }
  }

  public static function getExtLink($id) {
    switch ($id) {
    	case "1":
    		return 'https://www.churchofscotland.org.uk/__data/assets/pdf_file/0011/68708/ER-Gaelic-HANDBOOK-V5.pdf';
    		break;
      case "2":
    		return 'https://www.cne-siar.gov.uk/media/4714/gaelicenglish.pdf';
    		break;
    	default:
    		return '';
    }
	}

}
