export const getImage = (image) => {
  if (!image) {
    return "/images/naruto.jpg";
  } else if (image.includes("http")) {
    return image;
  } else {
    const parts = image.split("/");
    const folder = parts[0];
    const subfolder = parts[1];
    const subsubfolder = parts[2];
    const filename = parts[3];

    return route("image.show", { folder, subfolder, subsubfolder, filename });
  }
};
