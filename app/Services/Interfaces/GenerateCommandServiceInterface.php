<?php

namespace App\Services\Interfaces;

interface GenerateCommandServiceInterface
{
    /**
     * Create a command line by given command name, command arguments and command short (-) options
     * Acceptable option keys:
     * hplan - display planet numbers
     * bj - begin date as an absolute Julian day number
     * solecl - solar eclipse
     * occult - occultation of planet or star by the moon
     * local  only with -solecl or -occult, if the next event of this
     *      kind is wanted for a given geogr. position.
     * lunecl - lunar eclipse
     * hev[type] - heliacal events,
     *      type 1 = heliacal rising
     *      type 2 = heliacal setting
     *      type 3 = evening first
     *      type 4 = morning last
     *      type 0 or missing = all four events are listed.
     * rise - rising and setting of a planet or star
     * metr - southern and northern meridian transit of a planet of star
     * total - total eclipse (only with -solecl, -lunecl)
     * partial - partial eclipse (only with -solecl, -lunecl)
     * annular - annular eclipse (only with -solecl)
     * anntot - annular-total (hybrid) eclipse (only with -solecl)
     * penumbral - penumbral lunar eclipse (only with -lunecl)
     * central - central eclipse (only with -solecl, nonlocal)
     * noncentral - non-central eclipse (only with -solecl, nonlocal)
     * norefrac - neglect refraction (with option -rise)
     * disccenter - find rise of disc center (with option -rise)
     * discbottom - find rise of disc bottom (with option -rise)
     * hindu - hindu version of sunrise (with option -rise)
     * p - planet. The codes for planet are:
     *      0 Sun (character zero)
     *      1 Moon (character 1)
     *      2 Mercury
     *      3 Venus
     *      4 Mars
     *      5 Jupiter
     *      6 Saturn
     *      7 Uranus
     *      8 Neptune
     *      9 Pluto
     *      m mean lunar node
     *      t true lunar node
     *      n nutation
     *      o obliquity of ecliptic
     *      q delta t
     *      y time equation
     *      b ayanamsha
     *      A mean lunar apogee (Lilith, Black Moon)
     *      B osculating lunar apogee
     *      c intp. lunar apogee
     *      g intp. lunar perigee
     *      C Earth (in heliocentric or barycentric calculation)
     *      F Ceres
     *      9 Pluto
     *      s -xs136199   Eris
     *      s -xs136472   Makemake
     *      s -xs136108   Haumea
     *      D Chiron
     *      E Pholus
     *      G Pallas
     *      H Juno
     *      I Vesta
     *      s minor planet, with MPC number given in -xs
     *      f fixed star, with name or number given in -xf option
     *      f -xfSirius   Sirius
     *      J Cupido
     *      K Hades
     *      L Zeus
     *      M Kronos
     *      N Apollon
     *      O Admetos
     *      P Vulkanus
     *      Q Poseidon
     *      R Isis (Sevin)
     *      S Nibiru (Sitchin)
     *      T Harrington
     *      U Leverrier's Neptune
     *      V Adams' Neptune
     *      W Lowell's Pluto
     *      X Pickering's Pluto
     *      Y Vulcan
     *      Z White Moon
     *      w Waldemath's dark Moon
     *      z hypothetical body, with number given in -xz
     * xf - fixed stars with number as value
     * house[long,lat,hsys] - houses can only be computed if option -ut is given
     * ut - values:
     *      A  equal
     *      B  Alcabitius
     *      C  Campanus
     *      D  equal / MC
     *      E  equal = A
     *      F  Carter poli-equatorial
     *      G  36 Gauquelin sectors
     *      H  horizon / azimuth
     *      I  Sunshine
     *      i  Sunshine alternative
     *      K  Koch
     *      L  Pullen S-delta
     *      M  Morinus
     *      N  Whole sign, Aries = 1st house
     *      O  Porphyry
     *      P  Placidus
     *      Q  Pullen S-ratio
     *      R  Regiomontanus
     *      S  Sripati
     *      T  Polich/Page ("topocentric")
     *      U  Krusinski-Pisa-Goelzer
     *      V  equal Vehlow
     *      W  equal, whole sign
     *      X  axial rotation system/ Meridian houses
     *      Y  APC houses
     * sid - sidereal, with number of method:
     *      0 for Fagan/Bradley
     *      1 for Lahiri
     *      2 for De Luce
     *      3 for Raman
     *      4 for Usha/Shashi
     *      5 for Krishnamurti
     *      6 for Djwhal Khul
     *      7 for Yukteshwar
     *      8 for J.N. Bhasin
     *      9 for Babylonian/Kugler 1
     *      10 for Babylonian/Kugler 2
     *      11 for Babylonian/Kugler 3
     *      12 for Babylonian/Huber
     *      13 for Babylonian/Eta Piscium
     *      14 for Babylonian/Aldebaran = 15 Tau
     *      15 for Hipparchos
     *      16 for Sassanian
     *      17 for Galact. Center = 0 Sag
     *      18 for J2000
     *      19 for J1900
     *      20 for B1950
     *      21 for Suryasiddhanta
     *      22 for Suryasiddhanta, mean Sun
     *      23 for Aryabhata
     *      24 for Aryabhata, mean Sun
     *      25 for SS Revati
     *      26 for SS Citra
     *      27 for True Citra
     *      28 for True Revati
     *      29 for True Pushya (PVRN Rao)
     *      30 for Galactic (Gil Brand)
     *      31 for Galactic Equator (IAU1958)
     *      32 for Galactic Equator
     *      33 for Galactic Equator mid-Mula
     *      34 for Skydram (Mardyks)
     *      35 for True Mula (Chandra Hari)
     *      36 Dhruva/Gal.Center/Mula (Wilhelm)
     *      37 Aryabhata 522
     *      38 Babylonian/Britton
     *      39 Vedic/Sheoran
     *      40 Cochrane (Gal.Center = 0 Cap)
     *      41 Galactic Equator (Fiorenza)
     *      42 Vettius Valens
     * hel - compute heliocentric positions
     * bary - compute barycentric positions (bar. earth instead of node)
     * topo[long,lat,elev] - topocentric positions. The longitude, latitude (degrees with
     *      DECIMAL fraction) and elevation (meters) can be given, with
     *      commas separated, + for east and north. If none are given,
     *      Greenwich is used 0.00,51.50,0
     * f - results:
     *      y year
     *      Y year.fraction_of_year
     *      p planet index
     *      P planet name
     *      J absolute juldate
     *      T date formatted like 23.02.1992
     *      t date formatted like 920223 for 1992 february 23
     *      L longitude in degree ddd mm'ss"
     *      l longitude decimal
     *      Z longitude ddsignmm'ss"
     *      S speed in longitude in degree ddd:mm:ss per day
     *      SS speed for all values specified in fmt
     *      s speed longitude decimal (degrees/day)
     *      ss speed for all values specified in fmt
     *      B latitude degree
     *      b latitude decimal
     *      R distance decimal in AU
     *      r distance decimal in AU, Moon in seconds parallax
     *      W distance decimal in light years
     *      w distance decimal in km
     *      q relative distance (1000=nearest, 0=furthest)
     *      A right ascension in hh:mm:ss
     *      a right ascension hours decimal
     *      D declination degree
     *      d declination decimal
     *      I azimuth degree
     *      i azimuth decimal
     *      H altitude degree
     *      h altitude decimal
     *      K altitude (with refraction) degree
     *      k altitude (with refraction) decimal
     *      G house position in degrees
     *      g house position in degrees decimal
     *      j house number 1.0 - 12.99999
     *      X x-, y-, and z-coordinates ecliptical
     *      x x-, y-, and z-coordinates equatorial
     *      U unit vector ecliptical
     *      u unit vector equatorial
     *      Q l, b, r, dl, db, dr, a, d, da, dd
     *      n nodes (mean): ascending/descending (Me - Ne); longitude decimal
     *      N nodes (osculating): ascending/descending, longitude; decimal
     *      f apsides (mean): perihelion, aphelion, second focal point; longitude dec.
     *      F apsides (osc.): perihelion, aphelion, second focal point; longitude dec.
     *      + phase angle
     *      - phase
     *       * elongation
     *      / apparent diameter of disc (without refraction)
     *      = magnitude
     *      v (reserved)
     *      V (reserved)
     *
     * @param string $command
     * @param array $options
     * @param array $arguments
     * @return mixed
     */
    public function generateCommand(string $command, array $options = [], array $arguments = []);
}
