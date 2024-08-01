<template>
  <!-- FOLDER-VALIDITY -->
  <svg v-if="name === 'folder'" xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960" width="84px"
    fill="#FEC63D">
    <path
      d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
  </svg>
  <!-- ADD-FILE -->
  <svg v-else-if="name === 'add-file'" xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960"
    width="84px" fill="#000000">
    <path
      d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
  </svg>
  <!-- ADD-Investigator -->
  <svg v-else-if="name === 'add-investigator'" xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960"
    width="84px" fill="#39A900">
    <path
      d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
  </svg>
  <!-- ADD-Collaborator -->
  <svg v-else-if="name === 'add-collabolator'" xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960"
    width="84px" fill="#FF8351">
    <path
      d="M453-280h60v-166h167v-60H513v-174h-60v174H280v60h173v166Zm27.27 200q-82.74 0-155.5-31.5Q252-143 197.5-197.5t-86-127.34Q80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.68q-54 54.31-127 86Q563-80 480.27-80Z" />
  </svg>
  <!-- FOLDER-PROJECT -->
  <svg v-else-if="name === 'project'" xmlns="http://www.w3.org/2000/svg" height="84px" viewBox="0 -960 960 960"
    width="84px" fill="#FEC63D">
    <path
      d="M168-192q-32 0-52-21t-20-51v-432q0-30 20-51t52-21h216l96 96h313q31 0 50.5 21t21.5 51H168v336l78-264h690l-85 285q-8 23-21 37t-38 14H168Z" />
  </svg>


  <!-- MENU -->
  <svg v-else-if="name === 'menu'" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960"
    width="35px" fill="#FFFFFF">
    <path d="M120-240v-60h720v60H120Zm0-210v-60h720v60H120Zm0-210v-60h720v60H120Z" />
  </svg>
  <!-- MENU-dasboard -->
  <svg v-else-if="name === 'dashboard'" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960"
    width="35px" fill="#FFFFFF">
    <path d="M510-570v-270h330v270H510ZM120-450v-390h330v390H120Zm390 330v-390h330v390H510Zm-390 0v-270h330v270H120Z" />
  </svg>
  <!-- MENU-investigators -->
  <svg v-else-if="name === 'investigators'" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960"
    width="35px" fill="#FFFFFF">
    <path
      d="M172-120q-41.78 0-59.39-39T124-230l248-280v-270h-52q-12.75 0-21.37-8.68-8.63-8.67-8.63-21.5 0-12.82 8.63-21.32 8.62-8.5 21.37-8.5h320q12.75 0 21.38 8.68 8.62 8.67 8.62 21.5 0 12.82-8.62 21.32-8.63 8.5-21.38 8.5h-52v270l248 280q29 32 11.39 71T788-120H172Z" />
  </svg>
  <!-- MENU-collaborators -->
  <svg v-else-if="name === 'collaborators'" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960"
    width="35px" fill="#FFFFFF">
    <path
      d="M80-160v-95q0-34 18-62.5t50-42.5q60-27 123.18-43.5Q334.37-420 400-420q33 0 66 4.67 33 4.66 66 11.33v53q-45 17-74.5 55T428-210v50H80Zm408 0v-51q0-22.89 11-42.45Q510-273 530-284q36-18.5 74.5-27.75T684-321q41 0 80 9t74 28q20 10 31 29.87T880-211v51H488Zm196-221q-39.48 0-66.74-27.26Q590-435.52 590-475q0-39.48 27.26-66.74Q644.52-569 684-569q39.48 0 66.74 27.26Q778-514.48 778-475q0 39.48-27.26 66.74Q723.48-381 684-381ZM400-481q-63 0-106.5-43.5T250-631q0-63 43.5-106.5T400-781q63 0 106.5 43.5T550-631q0 63-43.5 106.5T400-481Z" />
  </svg>
  <!-- MENU-document-categories-->
  <svg v-else-if="name === 'document-categories'" xmlns="http://www.w3.org/2000/svg" height="35px"
    viewBox="0 -960 960 960" width="35px" fill="#FFFFFF">
    <path
      d="m159-168-34-14q-31-13-41.5-45t3.5-63l72-156v278Zm160 88q-33 0-56.5-23.5T239-160v-240l106 294q3 7 6 13.5t8 12.5h-40Zm206-4q-32 12-62-3t-42-47L243-622q-12-32 2-62.5t46-41.5l302-110q32-12 62 3t42 47l178 488q12 32-2 62.5T827-194L525-84Zm-86-476q17 0 28.5-11.5T479-600q0-17-11.5-28.5T439-640q-17 0-28.5 11.5T399-600q0 17 11.5 28.5T439-560Z" />
  </svg>
  <!-- MENU-settings -->
  <svg v-else-if="name === 'settings'" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960"
    width="35px" fill="#FFFFFF">
    <path
      d="m388-80-20-126q-19-7-40-19t-37-25l-118 54-93-164 108-79q-2-9-2.5-20.5T185-480q0-9 .5-20.5T188-521L80-600l93-164 118 54q16-13 37-25t40-18l20-127h184l20 126q19 7 40.5 18.5T669-710l118-54 93 164-108 77q2 10 2.5 21.5t.5 21.5q0 10-.5 21t-2.5 21l108 78-93 164-118-54q-16 13-36.5 25.5T592-206L572-80H388Zm92-270q54 0 92-38t38-92q0-54-38-92t-92-38q-54 0-92 38t-38 92q0 54 38 92t92 38Z" />
  </svg>
  <!-- MENU-logOut -->
  <svg v-else-if="name === 'logout'" xmlns="http://www.w3.org/2000/svg" height="35px" viewBox="0 -960 960 960"
    width="35px" fill="#FFFFFF">
    <path
      d="M180-120q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h299v60H180v600h299v60H180Zm486-185-43-43 102-102H360v-60h363L621-612l43-43 176 176-174 174Z" />
  </svg>



  <!-- ADMIN -->
  <svg v-else-if="name === 'admin'" xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960"
    width="40px" fill="#000000">
    <path
      d="M690.88-270q25.88 0 44-19T753-333.88q0-25.88-18.12-44t-44-18.12Q665-396 646-377.88q-19 18.12-19 44T646-289q19 19 44.88 19Zm-1.38 125q33.5 0 60.5-14t46-40q-26-14-51.96-21t-54-7q-28.04 0-54.54 7T584-199q19 26 45.5 40t60 14Zm3 65Q615-80 560-135.5T505-267q0-78.43 54.99-133.72Q614.98-456 693-456q77 0 132.5 55.28Q881-345.43 881-267q0 76-55.5 131.5T692.5-80ZM480-80q-138-32-229-156.5T160-522v-239l320-120 320 120v270q-25-12-52-18.5t-55-6.5q-102.74 0-175.37 72.92Q445-370.16 445-267q0 48 19.5 94T518-93q-9 5-19 7.5T480-80Z" />
  </svg>

  <!-- INVESTIGATOR -->
  <svg v-else-if="name === 'investigator'" xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960"
    width="40px" fill="#5f6368">
    <path
      d="M181.33-120q-44.85 0-63.76-41.17-18.9-41.16 11.1-75.5L368-513.33v-260h-48q-14.17 0-23.75-9.62t-9.58-23.83q0-14.22 9.58-23.72 9.58-9.5 23.75-9.5h320q14.17 0 23.75 9.62 9.58 9.61 9.58 23.83 0 14.22-9.58 23.72-9.58 9.5-23.75 9.5h-48v260l239.33 276.66q30 34.34 11.1 75.5Q823.52-120 778.67-120H181.33Z" />
  </svg>

  <!-- COLLABORATOR -->
  <svg v-else-if="name === 'collaborator'" xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960"
    width="40px" fill="#000000">
    <path
      d="M140-80q-24 0-42-18t-18-42v-480q0-24 18-42t42-18h250v-140q0-24 18-42t42-18h60q24 0 42 18t18 42v140h250q24 0 42 18t18 42v480q0 24-18 42t-42 18H140Zm92-167h239v-14q0-18-9-32t-23-19q-32-11-50-14.5t-35-3.5q-19 0-40.5 4.5T265-312q-15 5-24 19t-9 32v14Zm336-67h170v-50H568v50Zm-214-50q23 0 38.5-15.5T408-418q0-23-15.5-38.5T354-472q-23 0-38.5 15.5T300-418q0 23 15.5 38.5T354-364Zm214-63h170v-50H568v50ZM450-590h60v-230h-60v230Z" />
  </svg>
  <!-- GUEST -->
  <svg v-else-if="name === 'guest'" xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960"
    width="40px" fill="#000000">
    <path
      d="M222-255q63-40 124.5-60.5T480-336q72 0 134 20.5T739-255q44-54 62.5-109T820-480q0-145-97.5-242.5T480-820q-145 0-242.5 97.5T140-480q0 61 19 116t63 109Zm257.81-195q-57.81 0-97.31-39.69-39.5-39.68-39.5-97.5 0-57.81 39.69-97.31 39.68-39.5 97.5-39.5 57.81 0 97.31 39.69 39.5 39.68 39.5 97.5 0 57.81-39.69 97.31-39.68 39.5-97.5 39.5Zm-.21 370q-83.15 0-156.28-31.5t-127.22-86Q142-252 111-324.84 80-397.68 80-480.5t31.5-155.66Q143-709 197.5-763t127.34-85.5Q397.68-880 480.5-880t155.66 31.5Q709-817 763-763t85.5 127Q880-563 880-480.27q0 82.74-31.5 155.5Q817-252 763-197.5t-127.13 86Q562.74-80 479.6-80Z" />
  </svg>


  <!-- TABLE - edit -->
  <svg v-else-if="name === 'edit'" xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960"
    width="40px" fill="#000000">
    <path
      d="M360-360v-170l382-382q9-9 20-13t22-4q11 0 22.5 4.5T827-911l83 84q9 9 13.5 20t4.5 22q0 11-4.5 22.5T910-742L530-360H360Zm440-355 68-70-84-84-69 69 85 85ZM180-120q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h405L300-555v255h252l288-288v408q0 24-18 42t-42 18H180Z" />
  </svg>
  <!-- TABLE - delete -->
  <svg v-else-if="name === 'delete'" xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960"
    width="25px" fill="#000000">
    <path
      d="M261-120q-24 0-42-18t-18-42v-570h-41v-60h188v-30h264v30h188v60h-41v570q0 24-18 42t-42 18H261Zm106-146h60v-399h-60v399Zm166 0h60v-399h-60v399Z" />
  </svg>

  <!-- ACTIONS -->
  <!-- ADD -->
  <svg v-else-if="name === 'add'" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px"
    fill="#FFFFFF">
    <path d="M440.39-440.39H185.87v-79.22h254.52V-774.7h79.22v255.09H774.7v79.22H519.61v254.52h-79.22v-254.52Z" />
  </svg>
  <!-- ADD CIRCLE -->
  <svg v-else-if="name === 'add-circle'" xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960"
    width="40px" fill="#5f6368">
    <path
      d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
  </svg>
  <!-- CANCEL -->
  <svg v-else-if="name === 'cancel'" xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960"
    width="25px" fill="#EA3323">
    <path
      d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
  </svg>
  <!-- SEARCH -->
  <svg v-else-if="name === 'search'" xmlns="http://www.w3.org/2000/svg" height="25px" viewBox="0 -960 960 960"
    width="25px" fill="#999999">
    <path
      d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
  </svg>
  <!-- BACK -->
  <svg v-else-if="name === 'back'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
    width="24px" fill="#000000">
    <path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z" />
  </svg>
  <!-- SEND -->
  <svg v-else-if="name === 'send'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
    width="24px" fill="#5f6368">
    <path d="M120-160v-240l320-80-320-80v-240l760 320-760 320Z" />
  </svg>
  <!-- ARROW DOWN -->
  <svg v-else-if="name === 'arrow-down'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
    width="24px" fill="#5f6368">
    <path d="M480-360 280-560h400L480-360Z" />
  </svg>
  <!-- ARROW UP -->
  <svg v-else-if="name === 'arrow-up'" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
    width="24px" fill="#5f6368">
    <path d="m280-400 200-200 200 200H280Z" />
  </svg>


  <!-- FILES -->
  <!-- PDF -->
  <svg v-else-if="name === 'pdf'" xmlns="http://www.w3.org/2000/svg" height="58px" viewBox="0 -960 960 960" width="58px"
    fill="#EA3323">
    <path
      d="M331-431h37v-83h48q15.73 0 26.36-10.64Q453-535.28 453-551v-48q0-15.72-10.64-26.36Q431.73-636 416-636h-85v205Zm37-120v-48h48v48h-48Zm129 120h84q15 0 26-10.64 11-10.63 11-26.36v-131q0-15.72-11-26.36Q596-636 581-636h-84v205Zm37-37v-131h47v131h-47Zm133 37h37v-83h50v-37h-50v-48h50v-37h-87v205ZM260-200q-24 0-42-18t-18-42v-560q0-24 18-42t42-18h560q24 0 42 18t18 42v560q0 24-18 42t-42 18H260ZM140-80q-24 0-42-18t-18-42v-620h60v620h620v60H140Z" />
  </svg>
  <!-- Word -->
  <svg v-else-if="name === 'word' || name == 'docx'" xmlns="http://www.w3.org/2000/svg" height="58px"
    viewBox="0 -960 960 960" width="58px" fill="#0000F5">
    <path
      d="M319-250h322v-60H319v60Zm0-170h322v-60H319v60ZM220-80q-24 0-42-18t-18-42v-680q0-24 18-42t42-18h361l219 219v521q0 24-18 42t-42 18H220Zm331-554h189L551-820v186Z" />
  </svg>
  <!-- Excel -->
  <svg v-else-if="name === 'excel'" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
    width="48px" fill="#48752C">
    <path
      d="M319-250h322v-60H319v60Zm0-170h322v-60H319v60ZM220-80q-24 0-42-18t-18-42v-680q0-24 18-42t42-18h361l219 219v521q0 24-18 42t-42 18H220Zm331-554h189L551-820v186Z" />
  </svg>
  <!-- File -->
  <svg v-else-if="name === 'file'" xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960"
    width="48px" fill="#48752C">
    <path
      d="M319-250h322v-60H319v60Zm0-170h322v-60H319v60ZM220-80q-24 0-42-18t-18-42v-680q0-24 18-42t42-18h361l219 219v521q0 24-18 42t-42 18H220Zm331-554h189L551-820v186Z" />
  </svg>

  <!-- Image -->
  <svg v-else-if="name == 'png' || name == 'jpg' || name == 'jpeg'" xmlns="http://www.w3.org/2000/svg" width="68px"
    height="68px" viewBox="0 0 24 24">
    <path fill="#a51d2d"
      d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v14q0 .825-.587 1.413T19 21zm0-2h14V5H5zm1-2h12l-3.75-5l-3 4L9 13zm-1 2V5z" />
  </svg>

  <!-- SHARED FOLDER -->

  <svg v-else-if="name == 'sharedFolder'" xmlns="http://www.w3.org/2000/svg" width="84px" height="84px"
    viewBox="0 0 32 32">
    <defs>
      <clipPath id="vscodeIconsFolderTypeShared0">
        <path fill="none" d="M0 0h32v32H0z" />
      </clipPath>
    </defs>
    <g clip-path="url(#vscodeIconsFolderTypeShared0)">
      <path fill="#39A900" d="M27.5 5.5h-9.3l-2.1 4.2H4.4v16.8h25.2v-21Zm0 4.2h-8.2l1.1-2.1h7.1Z" />
      <path fill="#ade193"
        d="M15 11a4 4 0 0 0-4 4v6a4 4 0 0 0 4 4h6.17a3 3 0 1 0 0-2H15a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2h2a4 4 0 0 0-4-4Zm12 20a4 4 0 0 0 4-4v-6a4 4 0 0 0-4-4h-6.17a3 3 0 1 0 0 2H27a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-6a2 2 0 0 1-2-2h-2a4 4 0 0 0 4 4Z" />
      <path fill="#ade193" d="M17 26.5v.5h2v-.5Z" />
    </g>
  </svg>
  <!-- EYE -->
  <svg v-else-if="name == 'eye'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
    <path fill="#39A900"
      d="M12 9a3 3 0 0 0-3 3a3 3 0 0 0 3 3a3 3 0 0 0 3-3a3 3 0 0 0-3-3m0 8a5 5 0 0 1-5-5a5 5 0 0 1 5-5a5 5 0 0 1 5 5a5 5 0 0 1-5 5m0-12.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5" />
  </svg>
  <!-- LINK -->
  <svg v-else-if="name == 'link'" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24">
    <path fill="#39A900"
      d="M11 17H7q-2.075 0-3.537-1.463T2 12t1.463-3.537T7 7h4v2H7q-1.25 0-2.125.875T4 12t.875 2.125T7 15h4zm-3-4v-2h8v2zm5 4v-2h4q1.25 0 2.125-.875T20 12t-.875-2.125T17 9h-4V7h4q2.075 0 3.538 1.463T22 12t-1.463 3.538T17 17z" />
  </svg>

  <svg v-else-if="name == 'pptx'" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 16 16">
    <path fill="#39A900" fill-rule="evenodd"
      d="M14 4.5V11h-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.5 11.85h1.6q.434 0 .732.179q.302.175.46.477t.158.677t-.16.677q-.159.299-.464.474a1.45 1.45 0 0 1-.732.173H2.29v1.342H1.5zm2.06 1.714a.8.8 0 0 0 .085-.381q0-.34-.185-.521q-.185-.182-.513-.182h-.659v1.406h.66a.8.8 0 0 0 .374-.082a.57.57 0 0 0 .238-.24m1.302-1.714h1.6q.434 0 .732.179q.302.175.46.477t.158.677t-.16.677q-.158.299-.464.474a1.45 1.45 0 0 1-.732.173h-.803v1.342h-.79zm2.06 1.714a.8.8 0 0 0 .085-.381q0-.34-.185-.521q-.184-.182-.513-.182H5.65v1.406h.66a.8.8 0 0 0 .374-.082a.57.57 0 0 0 .238-.24m2.852 2.285v-3.337h1.137v-.662H7.846v.662H8.98v3.337zm3.796-3.999h.893l-1.274 2.007l1.254 1.992h-.908l-.85-1.415h-.035l-.853 1.415h-.861l1.24-2.016l-1.228-1.983h.931l.832 1.439h.035z" />
  </svg>

  <svg v-else-if="name == 'xlsx' || name == 'xlsm' || name == 'xlsb' || name == 'xltx'" xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 48 48">
    <g fill="none" stroke="#39A900" stroke-linecap="round" stroke-width="4">
      <path stroke-linejoin="round" d="M8 15V6a2 2 0 0 1 2-2h28a2 2 0 0 1 2 2v36a2 2 0 0 1-2 2H10a2 2 0 0 1-2-2v-9" />
      <path d="M31 15h3m-6 8h6m-6 8h6" />
      <path stroke-linejoin="round" d="M4 15h18v18H4zm6 6l6 6m0-6l-6 6" />
    </g>
  </svg>

  <svg v-else xmlns="http://www.w3.org/2000/svg" width="68px" height="68px" viewBox="0 0 24 24">
    <g fill="none">
      <path d="M0 0h24v24H0z" />
      <path fill="#26a269"
        d="M14.486 3.143a1 1 0 0 1 .692 1.233l-4.43 15.788a1 1 0 0 1-1.926-.54l4.43-15.788a1 1 0 0 1 1.234-.693M7.207 7.05a1 1 0 0 1 0 1.414L3.672 12l3.535 3.535a1 1 0 1 1-1.414 1.415L1.55 12.707a1 1 0 0 1 0-1.414L5.793 7.05a1 1 0 0 1 1.414 0m9.586 1.414a1 1 0 1 1 1.414-1.414l4.243 4.243a1 1 0 0 1 0 1.414l-4.243 4.243a1 1 0 0 1-1.414-1.415L20.328 12z" />
    </g>
  </svg>

</template>

<script>
export default {
  props: {
    name: String,
  },
}
</script>
