export const getImage = (image) => {
  // Check if the thumbnail column is empty
  if (!image) {
    return "/images/naruto.jpg";
  }

  // Check if the thumbnail is a URL
  if (image.includes("http")) {
    return image;
  }

  // Check if the thumbnail appears not to be a path (no "/")
  if (!image.includes("/")) {
    return "/images/naruto.jpg"; // Default image
  }

  // If it's a path, process it
  const parts = image.split("/");
  const folder = parts[0];
  const subfolder = parts[1];
  const subsubfolder = parts[2];
  const filename = parts[3];

  return route("image.show", { folder, subfolder, subsubfolder, filename });
};
